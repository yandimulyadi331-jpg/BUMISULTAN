<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/presensi', App\Http\Controllers\Api\PresensiController::class);
Route::post('/presensi/log', [App\Http\Controllers\Api\PresensiController::class, 'log']);

// Real-time Notifications API Routes
Route::prefix('notifications')->group(function () {
    Route::get('/', [App\Http\Controllers\Api\NotificationController::class, 'getTodayNotifications']);
    Route::get('/count', [App\Http\Controllers\Api\NotificationController::class, 'getCount']);
    Route::get('/stats', [App\Http\Controllers\Api\NotificationController::class, 'getTodayStats']);
    Route::get('/type/{type}', [App\Http\Controllers\Api\NotificationController::class, 'getByType']);
    Route::post('/mark-read/{id}', [App\Http\Controllers\Api\NotificationController::class, 'markAsRead']);
    Route::post('/mark-all-read', [App\Http\Controllers\Api\NotificationController::class, 'markAllAsRead']);
    Route::post('/test', [App\Http\Controllers\Api\NotificationController::class, 'createTestNotification']);
    Route::delete('/cleanup', [App\Http\Controllers\Api\NotificationController::class, 'cleanupOldNotifications']);
});
