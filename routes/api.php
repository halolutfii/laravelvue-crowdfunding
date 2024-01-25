<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CampaignController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/generate-otpcode', [AuthController::class, 'generateOTP']);
    Route::post('/verifikasi-email', [AuthController::class, 'verification']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('email_verified');
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->middleware('email_verified');
});

Route::get('/get-profile', [AuthController::class, 'profile'])->middleware('auth');
Route::post('/update-profile', [AuthController::class, 'updateProfile'])->middleware('auth','email_verified');

Route::apiResource('campaign', CampaignController::class);

// Get all
Route::get('/getallcampaign', [CampaignController::class, 'getAll']);

