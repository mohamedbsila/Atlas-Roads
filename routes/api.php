<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Api\CommunityApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Review API Routes
Route::post('/reviews', [ReviewController::class, 'addReview']);
Route::put('/reviews/{id}', [ReviewController::class, 'updateReview']);
Route::patch('/reviews/{id}/flag', [ReviewController::class, 'flagReview']);
Route::delete('/reviews/{id}', [ReviewController::class, 'deleteReview']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Event API removed to avoid exposing /api/events routes

// Community API Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/communities', [CommunityApiController::class, 'index']);
    Route::get('/communities/{community}', [CommunityApiController::class, 'show']);
    Route::post('/communities', [CommunityApiController::class, 'store']);
    Route::put('/communities/{community}', [CommunityApiController::class, 'update']);
    Route::delete('/communities/{community}', [CommunityApiController::class, 'destroy']);
    Route::post('/communities/{community}/join', [CommunityApiController::class, 'join']);
    Route::post('/communities/{community}/leave', [CommunityApiController::class, 'leave']);
});
