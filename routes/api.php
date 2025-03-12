<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\PaymentStatisticsController;
use App\Http\Controllers\PaymentExportController;
use App\Http\Controllers\StripeWebhookController;



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'user']);
    Route::get('/all-users', [UserController::class, 'getAllUsers']);
    Route::post('/payment-links', [PaymentController::class, 'createPayment']);
    Route::get('/payment-links', [PaymentController::class, 'index']);
    Route::get('/transactions/report', [PaymentStatisticsController::class, 'index']);

    Route::get('/payment-links/{id}', [PaymentController::class, 'show']);
    Route::delete('/payment-links/{id}', [PaymentController::class, 'destroy']);

    Route::get('/export-payments/csv', [PaymentExportController::class, 'exportCSV']);
    Route::get('/export-payments/excel', [PaymentExportController::class, 'exportExcel']);
});

Route::get('/payments/{id}/receipt', [PdfController::class, 'downloadReceipt']);
Route::post('/stripe/webhook', [StripeWebhookController::class, 'handleWebhook']);
Route::post('/stripe/webhookExpiration', [StripeWebhookController::class, 'handleWebhook']);

Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);
