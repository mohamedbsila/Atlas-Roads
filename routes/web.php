<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\ClubController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\ClubMembershipController;
use App\Http\Controllers\ClubMessageController;

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
Route::post('/clubs/{club}/join', [ClubMembershipController::class, 'store'])->middleware('auth');
Route::get('/admin/club-memberships', [ClubMembershipController::class, 'index'])->middleware(['auth']);
Route::post('/admin/club-memberships/{membership}/approve', [ClubMembershipController::class, 'approve'])->middleware(['auth']);
Route::post('/admin/club-memberships/{membership}/reject', [ClubMembershipController::class, 'reject'])->middleware(['auth']);

Route::middleware('auth')->group(function () {
    Route::post('/recommendations', [RecommendationController::class, 'store']);
    Route::get('/recommendations', [RecommendationController::class, 'index']);
    Route::get('/recommendations/{recommendation}/edit', [RecommendationController::class, 'edit'])->name('recommendations.edit');

     Route::put('/recommendations/{recommendation}', [RecommendationController::class, 'update'])->name('recommendations.update');
    Route::delete('/recommendations/{recommendation}', [RecommendationController::class, 'destroy'])->name('recommendations.destroy');

});
Route::middleware('auth')->group(function () {
    Route::post('/recommendations', [RecommendationController::class, 'store'])->name('recommendations.store');
});

// Review Routes (public)
Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update')->middleware('auth');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('auth');
Route::patch('/reviews/{review}/flag', [ReviewController::class, 'flag'])->name('reviews.flag')->middleware('auth');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/livre/{book}', [App\Http\Controllers\HomeController::class, 'show'])->name('book.show');

// Public club routes (no authentication required)
Route::get('/clubs-public/{club}', [ClubController::class, 'showPublic'])->name('clubs.show-public');
// Club chat messages (public read, auth write)
Route::get('/clubs/{club}/messages', [ClubMessageController::class, 'index'])->name('clubs.messages.index');
Route::post('/clubs/{club}/messages', [ClubMessageController::class, 'store'])->name('clubs.messages.store')->middleware('auth');

// Club membership routes
Route::post('/clubs/{club}/join', [ClubMembershipController::class, 'store'])->name('clubs.join')->middleware('auth');

Route::middleware('guest')->group(function () {
    Route::get('/register', Register::class)->name('register');
    Route::get('/login', Login::class)->name('login');

    Route::get('/login/forgot-password', ForgotPassword::class)->name('forgot-password');
    // Route for password reset with token
    Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
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
    Route::get('/static-sign-in', StaticSignIn::class)->name('sign-in');
    Route::get('/static-sign-up', StaticSignUp::class)->name('static-sign-up');
    Route::get('/rtl', Rtl::class)->name('rtl');
    Route::get('/virtual-reality', VirtualReality::class)->name('virtual-reality');
    Route::get('/user-profile', UserProfile::class)->name('user-profile');
    Route::get('/user-management', UserManagement::class)->name('user-management');
    
    // Books CRUD
    Route::resource('books', \App\Http\Controllers\BookController::class);

    // Clubs - CRUD complet
    Route::get('clubs', [ClubController::class, 'index'])->name('clubs.index');
    Route::get('clubs/create', [ClubController::class, 'create'])->name('clubs.create');
    Route::post('clubs', [ClubController::class, 'store'])->name('clubs.store');
    Route::get('clubs/{club}', [ClubController::class, 'show'])->name('clubs.show');
    Route::get('clubs/{club}/edit', [ClubController::class, 'edit'])->name('clubs.edit');
    Route::put('clubs/{club}', [ClubController::class, 'update'])->name('clubs.update');
    Route::delete('clubs/{club}', [ClubController::class, 'destroy'])->name('clubs.destroy');

    // Meetings - CRUD complet
    Route::get('meetings', [MeetingController::class, 'index'])->name('meetings.index');
    Route::get('meetings/{meeting}', [MeetingController::class, 'show'])->name('meetings.show');
    Route::get('clubs/{club}/meetings/create', [MeetingController::class, 'create'])->name('clubs.meetings.create');
    Route::post('clubs/{club}/meetings', [MeetingController::class, 'store'])->name('clubs.meetings.store');
    Route::get('meetings/{meeting}/edit', [MeetingController::class, 'edit'])->name('meetings.edit');
    Route::put('meetings/{meeting}', [MeetingController::class, 'update'])->name('meetings.update');
    Route::delete('meetings/{meeting}', [MeetingController::class, 'destroy'])->name('meetings.destroy');

// Admin club membership management
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('club-memberships', [ClubMembershipController::class, 'index'])->name('admin.club-memberships.index');
    Route::patch('club-memberships/{membership}/approve', [ClubMembershipController::class, 'approve'])->name('admin.club-memberships.approve');
    Route::patch('club-memberships/{membership}/reject', [ClubMembershipController::class, 'reject'])->name('admin.club-memberships.reject');
});
    
    // Wishlist Routes - User
    Route::resource('wishlist', \App\Http\Controllers\WishlistController::class);
    Route::post('wishlist/{wishlist}/vote', [\App\Http\Controllers\WishlistController::class, 'toggleVote'])->name('wishlist.vote');
    Route::post('wishlist/{wishlist}/feedback', [\App\Http\Controllers\WishlistController::class, 'submitFeedback'])->name('wishlist.feedback');
    Route::get('wishlist-browse', [\App\Http\Controllers\WishlistController::class, 'browse'])->name('wishlist.browse');
    
    // Admin Wishlist Management
    Route::prefix('admin/wishlist')->name('admin.wishlist.')->group(function () {
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
