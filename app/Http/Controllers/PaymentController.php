<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Book;
use App\Models\BorrowRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Webhook;

class PaymentController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $myPayments = Payment::with(['book','borrowRequest','owner'])
            ->where('borrower_id', $userId)
            ->latest()->paginate(15, ['*'], 'my_page');

        $receivedPayments = Payment::with(['book','borrowRequest','borrower'])
            ->where('owner_id', $userId)
            ->latest()->paginate(15, ['*'], 'rec_page');

        return view('payments.index', compact('myPayments', 'receivedPayments'));
    }

    public function markAsPaid(Payment $payment, Request $request)
    {
        if ($payment->borrower_id !== Auth::id()) {
            return back()->withErrors(['error' => "Vous n'êtes pas autorisé à payer ce paiement."]);
        }

        if ($payment->status === 'paid') {
            return back()->with('success', 'Ce paiement est déjà marqué comme payé.');
        }

        $payment->status = 'paid';
        $payment->method = $request->input('method', 'cash');
        $payment->paid_at = now();
        $payment->save();

        // Si c'est un achat définitif, transférer la propriété du livre
        if ($payment->isPurchase()) {
            $book = $payment->book;
            if ($book) {
                // Transférer la propriété au borrower
                $book->ownerId = $payment->borrower_id;
                // Le livre devient disponible (ou on peut décider de le rendre indisponible, au choix)
                $book->is_available = true;
                $book->save();

                // Invalider toutes les demandes d'emprunt en attente pour ce livre
                \App\Models\BorrowRequest::where('book_id', $book->id)
                    ->whereIn('status', [\App\Enums\RequestStatus::PENDING, \App\Enums\RequestStatus::APPROVED])
                    ->update(['status' => \App\Enums\RequestStatus::REJECTED]);
            }
        }

        return back()->with('success', 'Paiement marqué comme payé.');
    }

    /**
     * Démarrer un achat définitif d'un livre avec Stripe
     */
    public function purchase(Book $book)
    {
        $userId = Auth::id();
        if (!$userId) return redirect()->route('login');

        // Empêcher d'acheter son propre livre
        if ($book->ownerId == $userId) {
            return back()->withErrors(['error' => "Vous ne pouvez pas acheter votre propre livre."]);
        }

        // Vérifier que le livre est disponible
        if (!$book->is_available) {
            return back()->withErrors(['error' => "Ce livre n'est pas disponible à l'achat pour le moment."]);
        }

        $currency = config('app.currency_symbol', '$');
        $price = (float) ($book->price ?? 0);
        if ($price <= 0) {
            return back()->withErrors(['error' => "Ce livre n'a pas de prix valide pour l'achat."]);
        }

        // Créer le paiement en base
        $payment = Payment::create([
            'borrower_id' => $userId,
            'owner_id' => $book->ownerId,
            'book_id' => $book->id,
            'borrow_request_id' => null,
            'amount_total' => round($price, 2),
            'amount_per_day' => null,
            'currency' => $currency,
            'status' => 'pending',
            'type' => 'purchase',
        ]);

        // Créer une session Stripe Checkout
        return $this->createStripeCheckoutSession($payment, $book);
    }

    /**
     * Créer une session Stripe Checkout pour un paiement d'emprunt
     */
    public function createBorrowCheckoutSession(BorrowRequest $borrowRequest)
    {
        $payment = $borrowRequest->payment;
        if (!$payment) {
            return back()->withErrors(['error' => 'Aucun paiement trouvé pour cette demande.']);
        }

        if ($payment->status === 'paid') {
            return back()->with('success', 'Ce paiement est déjà effectué.');
        }

        $book = $borrowRequest->book;
        return $this->createStripeCheckoutSession($payment, $book, $borrowRequest);
    }

    /**
     * Créer une session Stripe Checkout
     */
    private function createStripeCheckoutSession(Payment $payment, Book $book, ?BorrowRequest $borrowRequest = null)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $user = Auth::user();
        
        // Déterminer le montant en centimes (Stripe utilise les centimes)
        $amountInCents = (int) ($payment->amount_total * 100);
        
        // Description du produit
        $description = $payment->isPurchase() 
            ? "Achat définitif du livre: {$book->title}"
            : "Emprunt du livre: {$book->title}";

        try {
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd', // ou 'eur' selon votre devise
                        'product_data' => [
                            'name' => $book->title,
                            'description' => $description,
                            'images' => [$book->image_url],
                        ],
                        'unit_amount' => $amountInCents,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'customer_email' => $user->email,
                'client_reference_id' => $payment->id,
                'metadata' => [
                    'payment_id' => $payment->id,
                    'book_id' => $book->id,
                    'type' => $payment->type,
                ],
                'success_url' => route('payments.success') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('payments.cancel', ['payment' => $payment->id]),
            ]);

            // Sauvegarder l'ID de session
            $payment->stripe_session_id = $session->id;
            $payment->save();

            // Rediriger vers Stripe Checkout
            return redirect($session->url);

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur Stripe: ' . $e->getMessage()]);
        }
    }

    /**
     * Page de succès après paiement
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('payments.index')->with('error', 'Session invalide.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = StripeSession::retrieve($sessionId);
            
            // Récupérer le paiement
            $payment = Payment::find($session->client_reference_id);
            
            if (!$payment) {
                return redirect()->route('payments.index')->with('error', 'Paiement introuvable.');
            }

            // Vérifier si le paiement n'est pas déjà traité
            if ($payment->status !== 'paid') {
                $payment->status = 'paid';
                $payment->method = 'stripe';
                $payment->paid_at = now();
                $payment->stripe_payment_intent_id = $session->payment_intent;
                $payment->save();

                // Si c'est un achat définitif, transférer la propriété
                if ($payment->isPurchase()) {
                    $book = $payment->book;
                    if ($book) {
                        $book->ownerId = $payment->borrower_id;
                        $book->is_available = true;
                        $book->save();

                        // Invalider les demandes d'emprunt actives
                        BorrowRequest::where('book_id', $book->id)
                            ->whereIn('status', [\App\Enums\RequestStatus::PENDING, \App\Enums\RequestStatus::APPROVED])
                            ->update(['status' => \App\Enums\RequestStatus::REJECTED]);
                    }
                }
            }

            return redirect()->route('payments.index')->with('success', 'Paiement effectué avec succès! 🎉');

        } catch (\Exception $e) {
            return redirect()->route('payments.index')->with('error', 'Erreur lors de la vérification du paiement.');
        }
    }

    /**
     * Page d'annulation
     */
    public function cancel(Payment $payment)
    {
        return redirect()->route('payments.index')
            ->with('info', 'Paiement annulé. Vous pouvez réessayer plus tard.');
    }

    /**
     * Webhook Stripe pour confirmation de paiement
     */
    public function webhook(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        $endpoint_secret = config('services.stripe.webhook_secret');

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
        } catch (\UnexpectedValueException $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Gérer l'événement
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            
            $payment = Payment::find($session->client_reference_id);
            
            if ($payment && $payment->status !== 'paid') {
                $payment->status = 'paid';
                $payment->method = 'stripe';
                $payment->paid_at = now();
                $payment->stripe_payment_intent_id = $session->payment_intent;
                $payment->stripe_customer_id = $session->customer ?? null;
                $payment->save();

                // Si achat, transférer la propriété
                if ($payment->isPurchase()) {
                    $book = $payment->book;
                    if ($book) {
                        $book->ownerId = $payment->borrower_id;
                        $book->is_available = true;
                        $book->save();

                        BorrowRequest::where('book_id', $book->id)
                            ->whereIn('status', [\App\Enums\RequestStatus::PENDING, \App\Enums\RequestStatus::APPROVED])
                            ->update(['status' => \App\Enums\RequestStatus::REJECTED]);
                    }
                }
            }
        }

        return response()->json(['status' => 'success']);
    }
}
