<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: __DIR__ . '/../..')
    ->withRouting(
        web: __DIR__ . '/../../routes/web.php',
        api: __DIR__ . '/../../routes/api.php',
        commands: __DIR__ . '/../../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\BorrowRequest;
use App\Models\User;
use App\Models\Book;
use App\Enums\RequestStatus;

echo "=== TEST DE LA CLASSE BORROW REQUEST ===" . PHP_EOL . PHP_EOL;

// Test 1: Statistiques générales
echo "1. STATISTIQUES GÉNÉRALES:" . PHP_EOL;
echo "- Total demandes: " . BorrowRequest::count() . PHP_EOL;
echo "- Demandes en attente: " . BorrowRequest::where('status', RequestStatus::PENDING)->count() . PHP_EOL;
echo "- Demandes approuvées: " . BorrowRequest::where('status', RequestStatus::APPROVED)->count() . PHP_EOL;
echo "- Demandes retournées: " . BorrowRequest::where('status', RequestStatus::RETURNED)->count() . PHP_EOL;
echo PHP_EOL;

// Test 2: Relations
echo "2. TEST DES RELATIONS:" . PHP_EOL;
$request = BorrowRequest::with(['borrower', 'owner', 'book'])->first();
if ($request) {
    echo "- Demande ID: " . $request->id . PHP_EOL;
    echo "- Emprunteur: " . $request->borrower->name . PHP_EOL;
    echo "- Propriétaire: " . $request->owner->name . PHP_EOL;
    echo "- Livre: " . $request->book->title . PHP_EOL;
    echo "- Statut: " . $request->status->label() . PHP_EOL;
    echo "- Durée: " . $request->getDurationInDays() . " jours" . PHP_EOL;
}
echo PHP_EOL;

// Test 3: Méthodes de la classe
echo "3. TEST DES MÉTHODES:" . PHP_EOL;
$pending = BorrowRequest::where('status', RequestStatus::PENDING)->first();
if ($pending) {
    echo "- Demande en attente trouvée (ID: " . $pending->id . ")" . PHP_EOL;
    echo "- isPending(): " . ($pending->isPending() ? 'Oui' : 'Non') . PHP_EOL;
    echo "- isActive(): " . ($pending->isActive() ? 'Oui' : 'Non') . PHP_EOL;
    echo "- isCompleted(): " . ($pending->isCompleted() ? 'Oui' : 'Non') . PHP_EOL;
    
    // Test d'approbation
    echo "- Approbation de la demande..." . PHP_EOL;
    $result = $pending->approveRequest();
    echo "- Résultat approbation: " . ($result ? 'Succès' : 'Échec') . PHP_EOL;
    echo "- Nouveau statut: " . $pending->status->label() . PHP_EOL;
}
echo PHP_EOL;

// Test 4: Créer une nouvelle demande
echo "4. TEST CRÉATION D'UNE NOUVELLE DEMANDE:" . PHP_EOL;
$users = User::all();
$books = Book::all();

if ($users->count() >= 2 && $books->count() >= 1) {
    $borrower = $users->where('id', '!=', $books->first()->ownerId)->first();
    $book = $books->first();
    
    $newRequest = BorrowRequest::createRequest(
        $borrower->id,
        $book->id,
        \Carbon\Carbon::now()->addDays(1),
        \Carbon\Carbon::now()->addDays(14),
        'Demande créée via le script de test'
    );
    
    echo "- Nouvelle demande créée (ID: " . $newRequest->id . ")" . PHP_EOL;
    echo "- Emprunteur: " . $newRequest->borrower->name . PHP_EOL;
    echo "- Livre: " . $newRequest->book->title . PHP_EOL;
    echo "- Statut: " . $newRequest->status->label() . PHP_EOL;
}

echo PHP_EOL . "=== FIN DU TEST ===" . PHP_EOL;