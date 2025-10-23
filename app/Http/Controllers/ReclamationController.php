<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
<<<<<<< HEAD
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ReclamationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reclamation::with('user')
            ->when($request->has('status') && $request->status !== 'all', function($q) use ($request) {
                return $q->where('statut', $request->status);
            })
            ->when($request->has('priority') && $request->priority !== 'all', function($q) use ($request) {
                return $q->where('priorite', $request->priority);
            })
            ->latest();

        $reclamations = $query->paginate(10);

        return view('reclamations.index', [
            'reclamations' => $reclamations,
            'currentStatus' => $request->input('status', 'all'),
            'currentPriority' => $request->input('priority', 'all')
        ]);
=======

class ReclamationController extends Controller
{
    public function index()
    {
        $reclamations = Reclamation::with('user')->latest()->paginate(10);
        return view('reclamations.index', compact('reclamations'));
>>>>>>> origin/complet
    }

    public function create()
    {
        return view('reclamations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required',
            'categorie' => 'required|string',
            'priorite' => 'required|in:basse,moyenne,haute',
        ]);

        Reclamation::create([
            'user_id' => Auth::id(),
            'titre' => $request->titre,
            'description' => $request->description,
            'categorie' => $request->categorie,
            'priorite' => $request->priorite,
        ]);

        return redirect()->route('reclamations.index')->with('success', 'Réclamation ajoutée avec succès');
    }

    public function show(Reclamation $reclamation)
    {
<<<<<<< HEAD
        $regenerate = request()->has('regenerate');
        $aiSolution = $this->generateAISolution($reclamation, $regenerate);
        
        return view('reclamations.show', compact('reclamation', 'aiSolution'));
    }

    /**
     * Génère une solution IA adaptée à la description de la réclamation
     */
    private function generateAISolution(Reclamation $reclamation, $forceRegenerate = false)
    {
        $cacheKey = 'ai_solution_' . $reclamation->id;
        
        if ($forceRegenerate) {
            Cache::forget($cacheKey);
        }
        
        if (!$forceRegenerate && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $solution = $this->callGeminiAPI($reclamation);
            Cache::put($cacheKey, $solution, 3600);
            return $solution;

        } catch (\Exception $e) {
            Log::error('Erreur génération solution IA: ' . $e->getMessage());
            $solution = $this->getDirectSolution($reclamation);
            Cache::put($cacheKey, $solution, 3600);
            return $solution;
        }
    }

    /**
     * Appelle l'API Gemini avec un prompt direct
     */
    private function callGeminiAPI(Reclamation $reclamation)
    {
        $client = new Client();
        $apiKey = env('GEMINI_API_KEY');
        
        if (empty($apiKey)) {
            throw new \Exception('Clé API Gemini non configurée');
        }

        $model = 'gemini-pro';
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

        $prompt = $this->buildDirectPrompt($reclamation);

        $response = $client->post($url, [
            'json' => [
                'contents' => [
                    [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.8,
                    'maxOutputTokens' => 500,
                    'topP' => 0.9
                ]
            ],
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30
        ]);

        $statusCode = $response->getStatusCode();
        $responseBody = $response->getBody()->getContents();

        if ($statusCode !== 200) {
            throw new \Exception("Erreur HTTP: {$statusCode}");
        }

        $responseData = json_decode($responseBody, true);

        if (!isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
            throw new \Exception('Structure de réponse API invalide');
        }

        return $responseData['candidates'][0]['content']['parts'][0]['text'];
    }

    /**
     * Construit un prompt direct et simple adapté par catégorie
     */
    private function buildDirectPrompt(Reclamation $reclamation)
    {
        $description = $reclamation->description;
        $title = $reclamation->titre;
        $category = $reclamation->categorie;
        $priority = $reclamation->priorite;

        $prompt = "Tu es un assistant expert en résolution de problèmes pour une bibliothèque. Analyse cette réclamation et fournis une solution directe et pratique.\n\n";
        $prompt .= "INFORMATIONS DE LA RÉCLAMATION :\n";
        $prompt .= "Titre : {$title}\n";
        $prompt .= "Description : {$description}\n";
        $prompt .= "Catégorie : {$category}\n";
        $prompt .= "Priorité : {$priority}\n\n";
        
        // Instructions spécifiques par catégorie
        $categoryInstructions = $this->getCategoryInstructions($category);
        $prompt .= "CONTEXTE SPÉCIFIQUE ({$category}) :\n";
        $prompt .= $categoryInstructions . "\n\n";
        
        $prompt .= "INSTRUCTIONS GÉNÉRALES :\n";
        $prompt .= "- Réponds UNIQUEMENT en français\n";
        $prompt .= "- Sois direct et concis (maximum 200 mots)\n";
        $prompt .= "- Propose une solution pratique et réalisable\n";
        $prompt .= "- Adapte ton ton selon la priorité (urgent pour haute priorité)\n";
        $prompt .= "- Ne donne pas d'étapes numérotées, mais une réponse fluide\n";
        $prompt .= "- Commence directement par la solution sans introduction\n";
        $prompt .= "- Utilise un ton professionnel mais accessible\n\n";
        $prompt .= "SOLUTION :";

        return $prompt;
    }

    /**
     * Retourne les instructions spécifiques selon la catégorie
     */
    private function getCategoryInstructions($category)
    {
        $instructions = [
            'technique' => "Concentre-toi sur les solutions techniques : diagnostic, résolution de bugs, optimisation de performance, problèmes de connexion, etc. Propose des étapes de dépannage et des solutions de contournement si nécessaire.",
            
            'livre' => "Concentre-toi sur les services liés aux livres : résumés, recommandations, disponibilité, informations sur les ouvrages, suggestions de lecture, etc. Sois précis sur les délais et les modalités.",
            
            'compte' => "Concentre-toi sur les problèmes de compte utilisateur : connexion, mot de passe, profil, permissions, accès, etc. Propose des solutions de récupération et des alternatives.",
            
            'service' => "Concentre-toi sur l'amélioration des services : suggestions, retours d'expérience, qualité, satisfaction client, etc. Montre que les retours sont pris en compte.",
            
            'bibliotheque' => "Concentre-toi sur les informations générales de la bibliothèque : horaires, localisation, services, procédures, règlement, etc. Fournis des informations précises et utiles.",
            
            'emprunt' => "Concentre-toi sur les questions d'emprunt : délais, prolongations, limites, retours, réservations, etc. Explique clairement les règles et les possibilités d'extension.",
            
            'autre' => "Concentre-toi sur une approche générale mais personnalisée. Analyse le contenu de la demande pour proposer la solution la plus adaptée possible."
        ];
        
        return $instructions[strtolower($category)] ?? $instructions['autre'];
    }

    /**
     * Solution directe de secours adaptée par catégorie
     */
    private function getDirectSolution(Reclamation $reclamation)
    {
        $category = strtolower($reclamation->categorie);
        $priority = strtolower($reclamation->priorite);
        $description = $reclamation->description;
        $title = $reclamation->titre;
        
        // Solutions détaillées par catégorie
        $categorySolutions = [
            'technique' => $this->getTechnicalSolution($title, $description),
            'livre' => $this->getBookSolution($title, $description),
            'compte' => $this->getAccountSolution($title, $description),
            'service' => $this->getServiceSolution($title, $description),
            'bibliotheque' => $this->getLibrarySolution($title, $description),
            'emprunt' => $this->getBorrowSolution($title, $description),
            'autre' => $this->getGeneralSolution($title, $description)
        ];
        
        $baseSolution = $categorySolutions[$category] ?? $categorySolutions['autre'];
        
        // Ajouter l'urgence selon la priorité
        if ($priority === 'haute') {
            $baseSolution = "🚨 URGENT - " . $baseSolution . " Nous traitons votre demande en priorité absolue.";
        } elseif ($priority === 'moyenne') {
            $baseSolution = $baseSolution . " Nous vous tiendrons informé de l'avancement.";
        }
        
        return $baseSolution;
    }

    /**
     * Solution pour les problèmes techniques
     */
    private function getTechnicalSolution($title, $description)
    {
        if (stripos($description, 'connexion') !== false || stripos($title, 'connexion') !== false) {
            return "Problème de connexion détecté. Nous allons vérifier votre compte et rétablir l'accès. Vérifiez d'abord votre mot de passe et votre connexion internet. Si le problème persiste, nos techniciens interviendront dans l'heure.";
        }
        
        if (stripos($description, 'lent') !== false || stripos($description, 'performance') !== false) {
            return "Problème de performance identifié. Nous optimisons nos serveurs régulièrement. Essayez de vider votre cache navigateur et de vous reconnecter. Si le problème persiste, nous investiguerons côté serveur.";
        }
        
        return "Problème technique détecté. Notre équipe technique va diagnostiquer et corriger le dysfonctionnement. Nous vous contacterons avec une solution définitive dans les plus brefs délais.";
    }

    /**
     * Solution pour les demandes de livres
     */
    private function getBookSolution($title, $description)
    {
        if (stripos($description, 'résumé') !== false || stripos($description, 'resume') !== false) {
            return "Demande de résumé reçue. Nous allons vous fournir un résumé détaillé du livre demandé. Notre équipe de bibliothécaires prépare une analyse complète qui vous sera envoyée par email.";
        }
        
        if (stripos($description, 'disponible') !== false || stripos($description, 'disponibilité') !== false) {
            return "Vérification de disponibilité en cours. Nous allons consulter notre catalogue et vous proposer des alternatives si le livre n'est pas disponible. Vous recevrez une notification avec les options possibles.";
        }
        
        if (stripos($description, 'recommandation') !== false || stripos($description, 'suggestion') !== false) {
            return "Demande de recommandation reçue. Notre équipe va analyser vos préférences et vous proposer une sélection personnalisée de livres adaptés à vos goûts.";
        }
        
        return "Demande concernant un livre reçue. Nous allons traiter votre demande et vous fournir les informations ou services demandés. Notre équipe de bibliothécaires vous contactera rapidement.";
    }

    /**
     * Solution pour les problèmes de compte
     */
    private function getAccountSolution($title, $description)
    {
        if (stripos($description, 'mot de passe') !== false || stripos($description, 'password') !== false) {
            return "Problème de mot de passe identifié. Utilisez la fonction 'Mot de passe oublié' sur la page de connexion. Si le problème persiste, nous pouvons réinitialiser votre compte manuellement.";
        }
        
        if (stripos($description, 'profil') !== false || stripos($description, 'informations') !== false) {
            return "Demande de modification de profil reçue. Vous pouvez modifier vos informations personnelles dans la section 'Mon Profil'. Si vous rencontrez des difficultés, nous vous aiderons à mettre à jour vos données.";
        }
        
        return "Problème de compte détecté. Nous allons examiner votre profil et rétablir l'accès. Nos techniciens interviennent généralement dans l'heure pour résoudre les problèmes de compte.";
    }

    /**
     * Solution pour les réclamations de service
     */
    private function getServiceSolution($title, $description)
    {
        if (stripos($description, 'amélioration') !== false || stripos($description, 'suggestion') !== false) {
            return "Suggestion d'amélioration bien reçue. Notre équipe va étudier votre proposition et l'intégrer dans nos prochaines mises à jour. Nous apprécions votre contribution à l'amélioration de nos services.";
        }
        
        if (stripos($description, 'satisfaction') !== false || stripos($description, 'qualité') !== false) {
            return "Votre retour sur la qualité de nos services est important. Nous allons analyser vos commentaires et prendre les mesures nécessaires pour améliorer votre expérience utilisateur.";
        }
        
        return "Réclamation concernant nos services prise en compte. Nous allons examiner votre demande et améliorer ce point. Nous vous tiendrons informé des mesures prises pour résoudre cette situation.";
    }

    /**
     * Solution pour les problèmes de bibliothèque
     */
    private function getLibrarySolution($title, $description)
    {
        if (stripos($description, 'horaire') !== false || stripos($description, 'ouverture') !== false) {
            return "Information sur les horaires demandée. Notre bibliothèque est ouverte du lundi au vendredi de 8h à 18h, et le samedi de 9h à 16h. Nous sommes fermés le dimanche et les jours fériés.";
        }
        
        if (stripos($description, 'localisation') !== false || stripos($description, 'adresse') !== false) {
            return "Information de localisation fournie. Notre bibliothèque se trouve au centre-ville, facilement accessible en transport en commun. Nous vous enverrons l'adresse exacte et les moyens d'accès par email.";
        }
        
        return "Demande concernant la bibliothèque reçue. Notre équipe va vous fournir toutes les informations nécessaires sur nos services, horaires et procédures.";
    }

    /**
     * Solution pour les problèmes d'emprunt
     */
    private function getBorrowSolution($title, $description)
    {
        if (stripos($description, 'retard') !== false || stripos($description, 'retour') !== false) {
            return "Problème de retour d'emprunt identifié. Vous pouvez prolonger votre emprunt en ligne ou nous contacter pour discuter d'une extension. Nous comprenons que des retards peuvent survenir.";
        }
        
        if (stripos($description, 'limite') !== false || stripos($description, 'quota') !== false) {
            return "Question sur les limites d'emprunt reçue. Vous pouvez emprunter jusqu'à 5 livres simultanément pour une durée de 3 semaines. Des extensions sont possibles si aucun autre utilisateur n'a réservé le livre.";
        }
        
        return "Demande concernant l'emprunt reçue. Notre équipe va vous aider avec votre demande d'emprunt et vous expliquer nos procédures et conditions.";
    }

    /**
     * Solution générale
     */
    private function getGeneralSolution($title, $description)
    {
        return "Votre demande est bien reçue. Notre équipe va analyser votre situation et vous apporter une réponse personnalisée rapidement. Nous vous tiendrons informé de l'avancement.";
=======
        return view('reclamations.show', compact('reclamation'));
>>>>>>> origin/complet
    }

    public function edit(Reclamation $reclamation)
    {
        return view('reclamations.edit', compact('reclamation'));
    }

    public function update(Request $request, Reclamation $reclamation)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required',
            'categorie' => 'required|string',
            'priorite' => 'required|in:basse,moyenne,haute',
            'statut' => 'required|in:en_attente,en_cours,resolue',
        ]);

        $reclamation->update($request->all());

        return redirect()->route('reclamations.index')->with('success', 'Réclamation mise à jour');
    }

    public function destroy(Reclamation $reclamation)
    {
        $reclamation->delete();
        return redirect()->route('reclamations.index')->with('success', 'Réclamation supprimée');
    }
<<<<<<< HEAD

    public function bienRecu(Reclamation $reclamation)
    {
        $reclamation->update([
            'statut' => 'en_cours'
        ]);

        return redirect()->back()->with('success', 'Réclamation marquée comme bien reçue.');
    }

    /**
     * Route pour régénérer une solution via AJAX
     */
    public function regenerate(Reclamation $reclamation)
    {
        try {
            // Vérifier que l'utilisateur a le droit de voir cette réclamation
            if (!Auth::check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Authentification requise'
                ], 401);
            }

            // Vérifier que l'utilisateur peut accéder à cette réclamation
            if (Auth::user()->role !== 'admin' && $reclamation->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Accès non autorisé à cette réclamation'
                ], 403);
            }

            $cacheKey = 'ai_solution_' . $reclamation->id;
            Cache::forget($cacheKey);
            
            $newSolution = $this->generateAISolution($reclamation, true);
            
            // Vérifier que la solution n'est pas vide
            if (empty(trim($newSolution))) {
                throw new \Exception('Solution générée vide');
            }
            
            return response()->json([
                'success' => true,
                'solution' => $newSolution,
                'timestamp' => now()->format('d/m/Y H:i:s')
            ]);
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('Erreur API Gemini lors de la régénération: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Erreur de connexion avec le service IA. Veuillez réessayer.'
            ], 503);
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la régénération: ' . $e->getMessage());
            
            // Fournir une solution de secours
            $fallbackSolution = $this->getDirectSolution($reclamation);
            
            return response()->json([
                'success' => true,
                'solution' => $fallbackSolution,
                'fallback' => true,
                'message' => 'Solution générée automatiquement (service IA temporairement indisponible)'
            ]);
        }
    }

    /**
     * Affiche l'interface du chatbot
     */
    public function chatbot()
    {
        return view('reclamations.chatbot');
    }

    /**
     * Génère une solution via le chatbot
     */
    public function chatbotGenerate(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:1000',
            'category' => 'nullable|string|in:technique,livre,compte,service,bibliotheque,emprunt,autre',
            'priority' => 'nullable|string|in:basse,moyenne,haute'
        ]);

        try {
            // Créer une réclamation temporaire pour utiliser les méthodes existantes
            $tempReclamation = new Reclamation();
            $tempReclamation->titre = 'Demande via chatbot';
            $tempReclamation->description = $request->description;
            $tempReclamation->categorie = $request->category ?? 'autre';
            $tempReclamation->priorite = $request->priority ?? 'moyenne';

            // Générer la solution
            $solution = $this->generateAISolution($tempReclamation, true);

            return response()->json([
                'success' => true,
                'solution' => $solution,
                'category' => $tempReclamation->categorie,
                'priority' => $tempReclamation->priorite,
                'timestamp' => now()->format('d/m/Y H:i:s')
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur chatbot: ' . $e->getMessage());
            
            // Solution de secours
            $fallbackSolution = $this->getDirectSolution($tempReclamation ?? new Reclamation());
            
            return response()->json([
                'success' => true,
                'solution' => $fallbackSolution,
                'fallback' => true,
                'message' => 'Solution générée automatiquement (service IA temporairement indisponible)'
            ]);
        }
    }

    /**
     * Génère une solution pour une réclamation en utilisant l'API Gemini
     */
    public function generateSolution(Reclamation $reclamation)
    {
        try {
            $client = new Client();
            $apiKey = env('GEMINI_API_KEY');
            
            if (empty($apiKey)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Clé API Gemini non configurée.'
                ], 500);
            }

            $prompt = "Réponds directement en français à cette recherche de livre :\n\n";
            $prompt .= "Titre : " . $reclamation->titre . "\n";
            $prompt .= "Description : " . $reclamation->description . "\n\n";
            $prompt .= "Donne des suggestions de livres directement.";

            $response = $client->post("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key={$apiKey}", [
                'json' => [
                    'contents' => [
                        'parts' => [
                            ['text' => $prompt]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 500,
                    ]
                ],
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
                'timeout' => 30
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if (!isset($responseData['candidates'][0]['content']['parts'][0]['text'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Format de réponse inattendu.'
                ], 500);
            }

            $generatedText = $responseData['candidates'][0]['content']['parts'][0]['text'];

            return response()->json([
                'success' => true,
                'solution' => $generatedText
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la génération: ' . $e->getMessage()
            ], 500);
        }
    }
}
=======
    public function bienRecu(Reclamation $reclamation)
{
    $reclamation->update([
        'statut' => 'en_cours' // ou "reçue" selon ton workflow
    ]);

    return redirect()->back()->with('success', 'Réclamation marquée comme bien reçue.');
}

}
>>>>>>> origin/complet
