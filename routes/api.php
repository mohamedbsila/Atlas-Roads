<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReviewController;

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



Route::post('/reviews', [ReviewController::class, 'addReview']);
Route::put('/reviews/{id}', [ReviewController::class, 'updateReview']);
Route::patch('/reviews/{id}/flag', [ReviewController::class, 'flagReview']);
Route::delete('/reviews/{id}', [ReviewController::class, 'deleteReview']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
