<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;

use App\Http\Livewire\Auth\ForgotPassword;
use App\Http\Livewire\Auth\ResetPassword;
use App\Http\Livewire\Auth\Register;
use App\Http\Livewire\Auth\Login;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Billing;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Tables;
use App\Http\Livewire\StaticSignIn;
use App\Http\Livewire\StaticSignUp;
use App\Http\Livewire\Rtl;

use App\Http\Livewire\LaravelExamples\UserProfile;
use App\Http\Livewire\LaravelExamples\UserManagement;
use App\Http\Livewire\VirtualReality;
use Illuminate\Http\Request;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BorrowRequestTestController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Review Routes (public)
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update')->middleware('auth');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('auth');
Route::patch('/reviews/{review}/flag', [ReviewController::class, 'flag'])->name('reviews.flag')->middleware('auth');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/livre/{book}', [App\Http\Controllers\HomeController::class, 'show'])->name('book.show');

// Routes pour les réclamations (protégées par authentification)
Route::middleware('auth')->group(function () {
    // Routes pour les réclamations
    Route::resource('reclamations', ReclamationController::class)
        ->only(['index', 'create', 'store', 'show'])
        ->names('reclamations');
        
    // Route pour générer une solution avec Gemini
    Route::post('/reclamations/{reclamation}/generate-solution', [ReclamationController::class, 'generateSolution'])
        ->name('reclamations.generate-solution');
    

        // Route pour régénérer les solutions IA
    Route::post('/reclamations/{reclamation}/regenerate', [ReclamationController::class, 'regenerate'])
        ->name('reclamations.regenerate');
    
    // Routes pour le chatbot
    Route::get('/chatbot', [ReclamationController::class, 'chatbot'])
        ->name('reclamations.chatbot');
    Route::post('/chatbot/generate', [ReclamationController::class, 'chatbotGenerate'])
        ->name('reclamations.chatbot.generate');
    // Routes pour les solutions
    Route::get('/solutions', \App\Http\Livewire\Solutions\Index::class)->name('solutions.index');
    
    // Routes pour les solutions (imbriquées dans les réclamations)
    Route::resource('reclamations.solutions', \App\Http\Controllers\SolutionController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->names('solutions');
        
    // Middleware pour s'assurer que l'utilisateur est admin
    Route::middleware('admin')->group(function () {
        Route::get('/admin/reclamations', [ReclamationController::class, 'adminIndex'])
            ->name('admin.reclamations.index');
        Route::put('/admin/reclamations/{reclamation}/status', [ReclamationController::class, 'updateStatus'])
            ->name('admin.reclamations.update-status');
    });
    // Route de déconnexion
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');

    Route::get('/login/forgot-password', ForgotPassword::class)->name('forgot-password');
    // Route for password reset with token
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
    // Static sign-in / sign-up pages - only for guests (RedirectIfAuthenticated will send logged users to home)
    Route::get('/static-sign-in', StaticSignIn::class)->name('sign-in');
    Route::get('/static-sign-up', StaticSignUp::class)->name('static-sign-up');
});

Route::get('/test-gemini-debug', function() {
    try {
        $apiKey = env('GEMINI_API_KEY');
        $model = env('GEMINI_MODEL', 'gemini-1.5-flash');
        
        // Vérification 1 : Configuration
        $config = [
            '✅ API Key configurée' => !empty($apiKey),
            '📏 Longueur de la clé' => strlen($apiKey ?? ''),
            '📝 Modèle' => $model,
            '🔧 Cache vidé' => 'Exécutez: php artisan config:clear',
        ];
        
        if (empty($apiKey)) {
            return response()->json([
                'error' => '❌ Clé API non trouvée',
                'solution' => 'Ajoutez GEMINI_API_KEY dans votre fichier .env',
                'config' => $config
            ]);
        }
        
        // Vérification 2 : Test API simple
        $client = new Client();
        $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";
        
        $testPayload = [
            'contents' => [
                [
                    'parts' => [
                        ['text' => 'Réponds simplement "Bonjour, ça fonctionne !" en français.']
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.7,
                'maxOutputTokens' => 50
            ]
        ];
        
        Log::info('🚀 Test API Gemini', [
            'model' => $model,
            'url' => substr($url, 0, 100) . '...',
            'api_key_length' => strlen($apiKey)
        ]);
        
        $response = $client->post($url, [
            'json' => $testPayload,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'timeout' => 30,
            'http_errors' => false // Pour capturer toutes les réponses
        ]);
        
        $statusCode = $response->getStatusCode();
        $body = json_decode($response->getBody()->getContents(), true);
        
        if ($statusCode === 200) {
            $generatedText = $body['candidates'][0]['content']['parts'][0]['text'] ?? 'Texte non trouvé';
            
            Log::info('✅ API Gemini fonctionne !', [
                'generated_text' => $generatedText
            ]);
            
            return response()->json([
                'success' => '✅ API Gemini fonctionne parfaitement !',
                'status_code' => $statusCode,
                'generated_text' => $generatedText,
                'config' => $config,
                'full_response' => $body,
                'next_step' => '👉 Votre API fonctionne ! Le problème est ailleurs. Vérifiez les logs Laravel dans storage/logs/laravel.log'
            ]);
        } else {
            Log::error('❌ Erreur API Gemini', [
                'status_code' => $statusCode,
                'response' => $body
            ]);
            
            $diagnostics = [
                400 => '🔴 Requête mal formée - Vérifiez le format JSON',
                401 => '🔴 CLÉ API INVALIDE - Créez une nouvelle clé sur https://aistudio.google.com/app/apikey',
                403 => '🔴 API non activée OU quota dépassé - Activez l\'API sur Google Cloud Console',
                404 => '🔴 Modèle inexistant - Utilisez: gemini-1.5-flash ou gemini-1.5-pro',
                429 => '🟡 Trop de requêtes - Attendez quelques minutes',
                500 => '🟡 Erreur serveur Google - Réessayez plus tard',
            ];
            
            $solutions = [
                401 => 'RÉVOQUEZ votre clé actuelle et créez-en une nouvelle sur https://aistudio.google.com/app/apikey',
                403 => 'Allez sur https://console.cloud.google.com et activez l\'API "Generative Language API"',
                404 => 'Changez GEMINI_MODEL=gemini-1.5-flash dans votre .env',
            ];
            
            return response()->json([
                'error' => "❌ Erreur HTTP {$statusCode}",
                'status_code' => $statusCode,
                'response_body' => $body,
                'config' => $config,
                'diagnostic' => $diagnostics[$statusCode] ?? '🔴 Erreur inconnue',
                'solution' => $solutions[$statusCode] ?? 'Consultez les logs Laravel pour plus de détails'
            ], 500);
        }
        
    } catch (\GuzzleHttp\Exception\ConnectException $e) {
        Log::error('🌐 Erreur de connexion', [
            'message' => $e->getMessage()
        ]);
        
        return response()->json([
            'error' => '🌐 Impossible de se connecter à l\'API Gemini',
            'message' => $e->getMessage(),
            'solutions' => [
                '1. Vérifiez votre connexion internet',
                '2. Vérifiez que votre firewall/antivirus ne bloque pas les requêtes sortantes',
                '3. Testez manuellement: curl https://generativelanguage.googleapis.com',
                '4. Si vous êtes derrière un proxy, configurez-le dans Guzzle'
            ]
        ], 500);
        
    } catch (\Exception $e) {
        Log::error('💥 Erreur inattendue', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return response()->json([
            'error' => '💥 Erreur inattendue',
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => array_slice(explode("\n", $e->getTraceAsString()), 0, 5)
        ], 500);
    }
});
// Events (admin) - protect with auth and admin middleware
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/events', \App\Http\Livewire\Events\ListEvents::class)
        ->name('events.index');

    Route::get('/events/create', \App\Http\Livewire\Events\CreateEvent::class)
        ->name('events.create');

    Route::get('/events/{id}/edit', \App\Http\Livewire\Events\EditEvent::class)
        ->name('events.edit');
});

Route::middleware('auth')->group(function () {
    // Dashboard should be accessible to admin users only. Non-admins will get a 404 via the 'admin' middleware.
    Route::get('/dashboard', Dashboard::class)->middleware('admin')->name('dashboard');
    Route::get('/billing', Billing::class)->name('billing');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/tables', Tables::class)->name('tables');
    // NOTE: static sign-in/up are intentionally guest-only and defined above.
    Route::get('/rtl', Rtl::class)->name('rtl');
    Route::get('/virtual-reality', VirtualReality::class)->name('virtual-reality');
    Route::get('/user-profile', UserProfile::class)->name('user-profile');
    Route::get('/user-management', UserManagement::class)->name('user-management');
    
    // Books CRUD (admin only)
    Route::middleware(['admin'])->group(function () {
        Route::resource('books', \App\Http\Controllers\BookController::class);
    });
    
    // Wishlist Routes - User
    Route::resource('wishlist', \App\Http\Controllers\WishlistController::class);
    Route::post('wishlist/{wishlist}/vote', [\App\Http\Controllers\WishlistController::class, 'toggleVote'])->name('wishlist.vote');
    Route::post('wishlist/{wishlist}/feedback', [\App\Http\Controllers\WishlistController::class, 'submitFeedback'])->name('wishlist.feedback');
    Route::get('wishlist-browse', [\App\Http\Controllers\WishlistController::class, 'browse'])->name('wishlist.browse');
    
    // Admin Wishlist Management (admin only)
    Route::prefix('admin/wishlist')->name('admin.wishlist.')->middleware('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminWishlistController::class, 'index'])->name('index');
        Route::get('/dashboard', [\App\Http\Controllers\AdminWishlistController::class, 'dashboard'])->name('dashboard');
        Route::get('/{wishlist}', [\App\Http\Controllers\AdminWishlistController::class, 'show'])->name('show');
        Route::post('/{wishlist}/update-status', [\App\Http\Controllers\AdminWishlistController::class, 'updateStatus'])->name('update-status');
        Route::post('/{wishlist}/link-book', [\App\Http\Controllers\AdminWishlistController::class, 'linkToBook'])->name('link-book');
        Route::post('/{wishlist}/create-book', [\App\Http\Controllers\AdminWishlistController::class, 'createBook'])->name('create-book');
        Route::post('/bulk-update', [\App\Http\Controllers\AdminWishlistController::class, 'bulkUpdate'])->name('bulk-update');
    });

    // Reclamations CRUD
    Route::resource('reclamations', ReclamationController::class);
    Route::patch('/reclamations/{reclamation}/bien-recu', [ReclamationController::class, 'bienRecu'])
     ->name('reclamations.bienRecu');

    // Borrow Requests Management
    Route::get('borrow-requests', [\App\Http\Controllers\BorrowRequestController::class, 'index'])->name('borrow-requests.index');
    Route::post('borrow-requests', [\App\Http\Controllers\BorrowRequestController::class, 'store'])->name('borrow-requests.store');
    Route::patch('borrow-requests/{borrowRequest}/approve', [\App\Http\Controllers\BorrowRequestController::class, 'approve'])->name('borrow-requests.approve');
    Route::patch('borrow-requests/{borrowRequest}/reject', [\App\Http\Controllers\BorrowRequestController::class, 'reject'])->name('borrow-requests.reject');
    Route::patch('borrow-requests/{borrowRequest}/return', [\App\Http\Controllers\BorrowRequestController::class, 'markAsReturned'])->name('borrow-requests.return');
    Route::patch('borrow-requests/{borrowRequest}/cancel', [\App\Http\Controllers\BorrowRequestController::class, 'cancel'])->name('borrow-requests.cancel');
});

// Route de test pour BorrowRequest
Route::get('/test-borrow-request', [BorrowRequestTestController::class, 'test']);
