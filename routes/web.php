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
use App\Http\Controllers\PaymentController;

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

// Stripe Webhook (must be BEFORE auth middleware)
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');

// Review Routes (public)
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update')->middleware('auth');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('auth');
Route::patch('/reviews/{review}/flag', [ReviewController::class, 'flag'])->name('reviews.flag')->middleware('auth');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/livre/{book}', [App\Http\Controllers\HomeController::class, 'show'])->name('book.show');

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');

    Route::get('/login/forgot-password', ForgotPassword::class)->name('forgot-password');
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
    Route::get('/static-sign-in', StaticSignIn::class)->name('sign-in');
    Route::get('/static-sign-up', StaticSignUp::class)->name('static-sign-up');
});

// Events (admin) - protect with auth and policy checks
Route::middleware(['auth'])->group(function () {
    Route::get('/events', \App\Http\Livewire\Events\ListEvents::class)
        ->name('events.index');

    Route::get('/events/create', \App\Http\Livewire\Events\CreateEvent::class)
        ->name('events.create');

    Route::get('/events/{id}/edit', \App\Http\Livewire\Events\EditEvent::class)
        ->name('events.edit');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/billing', Billing::class)->name('billing');
    Route::get('/profile', Profile::class)->name('profile');
    Route::get('/tables', Tables::class)->name('tables');
    Route::get('/rtl', Rtl::class)->name('rtl');
    Route::get('/virtual-reality', VirtualReality::class)->name('virtual-reality');
    Route::get('/user-profile', UserProfile::class)->name('user-profile');
    Route::get('/user-management', UserManagement::class)->name('user-management');
    
    // Books CRUD
    Route::resource('books', \App\Http\Controllers\BookController::class);
    
    // Book PDF Download
    Route::get('books/{book}/download-pdf', [\App\Http\Controllers\BookController::class, 'downloadPdf'])->name('books.download-pdf');
    
    // Categories CRUD
    Route::resource('categories', \App\Http\Controllers\CategoryController::class);
    
    // Wishlist Routes - User
    Route::resource('wishlist', \App\Http\Controllers\WishlistController::class);
    Route::post('wishlist/{wishlist}/vote', [\App\Http\Controllers\WishlistController::class, 'toggleVote'])->name('wishlist.vote');
    Route::post('wishlist/{wishlist}/feedback', [\App\Http\Controllers\WishlistController::class, 'submitFeedback'])->name('wishlist.feedback');
    Route::get('wishlist-browse', [\App\Http\Controllers\WishlistController::class, 'browse'])->name('wishlist.browse');
    
    // Admin Wishlist Management
    Route::prefix('admin/wishlist')->name('admin.wishlist.')->middleware('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminWishlistController::class, 'index'])->name('index');
        Route::get('/dashboard', [\App\Http\Controllers\AdminWishlistController::class, 'dashboard'])->name('dashboard');
        Route::get('/{wishlist}', [\App\Http\Controllers\AdminWishlistController::class, 'show'])->name('show');
        Route::post('/{wishlist}/update-status', [\App\Http\Controllers\AdminWishlistController::class, 'updateStatus'])->name('update-status');
        Route::post('/{wishlist}/link-book', [\App\Http\Controllers\AdminWishlistController::class, 'linkToBook'])->name('link-book');
        Route::post('/{wishlist}/create-book', [\App\Http\Controllers\AdminWishlistController::class, 'createBook'])->name('create-book');
        Route::post('/bulk-update', [\App\Http\Controllers\AdminWishlistController::class, 'bulkUpdate'])->name('bulk-update');
    });

    // Admin Bibliotheques Management
    Route::prefix('admin/bibliotheques')->name('admin.bibliotheques.')->middleware('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BibliothequeController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\BibliothequeController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\BibliothequeController::class, 'store'])->name('store');
        Route::get('/statistics', [\App\Http\Controllers\Admin\BibliothequeController::class, 'statistics'])->name('statistics');
        Route::post('/ai/generate-description', [\App\Http\Controllers\Admin\BibliothequeController::class, 'generateDescription'])->name('ai.description');
        Route::post('/ai/suggest-names', [\App\Http\Controllers\Admin\BibliothequeController::class, 'suggestNames'])->name('ai.names');
        Route::post('/ai/generate-image', [\App\Http\Controllers\Admin\BibliothequeController::class, 'generateImage'])->name('ai.image');
        Route::post('/ai/generate-prompt', [\App\Http\Controllers\Admin\BibliothequeController::class, 'generateImagePrompt'])->name('ai.prompt');
        Route::get('/ai/test', [\App\Http\Controllers\Admin\BibliothequeController::class, 'testAI'])->name('ai.test');
        Route::get('/{bibliotheque}', [\App\Http\Controllers\Admin\BibliothequeController::class, 'show'])->name('show');
        Route::get('/{bibliotheque}/edit', [\App\Http\Controllers\Admin\BibliothequeController::class, 'edit'])->name('edit');
        Route::put('/{bibliotheque}', [\App\Http\Controllers\Admin\BibliothequeController::class, 'update'])->name('update');
        Route::delete('/{bibliotheque}', [\App\Http\Controllers\Admin\BibliothequeController::class, 'destroy'])->name('destroy');
    });

    // Admin Sections Management
    Route::prefix('admin/sections')->name('admin.sections.')->middleware('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SectionController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\SectionController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\SectionController::class, 'store'])->name('store');
        Route::get('/{section}', [\App\Http\Controllers\Admin\SectionController::class, 'show'])->name('show');
        Route::get('/{section}/edit', [\App\Http\Controllers\Admin\SectionController::class, 'edit'])->name('edit');
        Route::put('/{section}', [\App\Http\Controllers\Admin\SectionController::class, 'update'])->name('update');
        Route::delete('/{section}', [\App\Http\Controllers\Admin\SectionController::class, 'destroy'])->name('destroy');
    });

    // Admin Rooms Management
    Route::prefix('admin/rooms')->name('admin.rooms.')->middleware('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RoomController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\RoomController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\RoomController::class, 'store'])->name('store');
        Route::get('/{room}/edit', [\App\Http\Controllers\Admin\RoomController::class, 'edit'])->name('edit');
        Route::put('/{room}', [\App\Http\Controllers\Admin\RoomController::class, 'update'])->name('update');
        Route::delete('/{room}', [\App\Http\Controllers\Admin\RoomController::class, 'destroy'])->name('destroy');
    });

    // Admin Reservations Management
    Route::prefix('admin/reservations')->name('admin.reservations.')->middleware('admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ReservationController::class, 'index'])->name('index');
    });

    // Room Reservations (Users)
    Route::get('rooms/search', [\App\Http\Controllers\RoomReservationController::class, 'search'])->name('rooms.search');
    Route::get('rooms/{room}', [\App\Http\Controllers\RoomReservationController::class, 'show'])->name('rooms.show');
    Route::post('room-reservations', [\App\Http\Controllers\RoomReservationController::class, 'store'])->name('room-reservations.store');
    Route::get('my-reservations', [\App\Http\Controllers\RoomReservationController::class, 'myReservations'])->name('room-reservations.my-reservations');
    Route::post('room-reservations/{reservation}/cancel', [\App\Http\Controllers\RoomReservationController::class, 'cancel'])->name('room-reservations.cancel');
    Route::post('rooms/check-availability', [\App\Http\Controllers\RoomReservationController::class, 'checkAvailability'])->name('rooms.check-availability');
    Route::post('rooms/suggest-times', [\App\Http\Controllers\RoomReservationController::class, 'suggestTimes'])->name('rooms.suggest-times');

    // Reclamations CRUD
    Route::resource('reclamations', ReclamationController::class);
    Route::patch('/reclamations/{reclamation}/bien-recu', [ReclamationController::class, 'bienRecu'])
     ->name('reclamations.bienRecu');
    
    // Reclamations AI Features
    Route::post('/reclamations/{reclamation}/regenerate', [ReclamationController::class, 'regenerate'])
        ->name('reclamations.regenerate');
    Route::get('/chatbot', [ReclamationController::class, 'chatbot'])
        ->name('reclamations.chatbot');
    Route::post('/chatbot/generate', [ReclamationController::class, 'chatbotGenerate'])
        ->name('reclamations.chatbot.generate');
    
    // Solutions Routes
    Route::get('/solutions', \App\Http\Livewire\Solutions\Index::class)->name('solutions.index');
    Route::resource('reclamations.solutions', \App\Http\Controllers\SolutionController::class)
        ->only(['create', 'store', 'edit', 'update', 'destroy'])
        ->names('solutions');

    // Borrow Requests Management
    Route::get('borrow-requests', [\App\Http\Controllers\BorrowRequestController::class, 'index'])->name('borrow-requests.index');
    Route::post('borrow-requests', [\App\Http\Controllers\BorrowRequestController::class, 'store'])->name('borrow-requests.store');
    Route::patch('borrow-requests/{borrowRequest}/approve', [\App\Http\Controllers\BorrowRequestController::class, 'approve'])->name('borrow-requests.approve');
    Route::patch('borrow-requests/{borrowRequest}/reject', [\App\Http\Controllers\BorrowRequestController::class, 'reject'])->name('borrow-requests.reject');
    Route::patch('borrow-requests/{borrowRequest}/return', [\App\Http\Controllers\BorrowRequestController::class, 'markAsReturned'])->name('borrow-requests.return');
    Route::patch('borrow-requests/{borrowRequest}/cancel', [\App\Http\Controllers\BorrowRequestController::class, 'cancel'])->name('borrow-requests.cancel');
    
    // Payment Routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/{payment}/mark-paid', [PaymentController::class, 'markAsPaid'])->name('payments.mark-paid');
    Route::post('/books/{book}/purchase', [PaymentController::class, 'purchase'])->name('books.purchase');
    Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/cancel/{payment}', [PaymentController::class, 'cancel'])->name('payments.cancel');
    Route::post('/borrow-requests/{borrowRequest}/pay', [PaymentController::class, 'createBorrowCheckoutSession'])->name('borrow-requests.pay');
    
    // Route de déconnexion
    Route::post('/logout', function () {
        auth()->logout();
        return redirect('/');
    })->name('logout');
});

// Route de test pour BorrowRequest
Route::get('/test-borrow-request', [BorrowRequestTestController::class, 'test']);
