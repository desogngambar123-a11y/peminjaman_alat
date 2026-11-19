<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LoanController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/loans', [LoanController::class, 'store'])->name('loans.store');
    Route::patch('/loans/{loan}/approve', [LoanController::class, 'approve'])->name('loans.approve');
    Route::patch('/loans/{loan}/reject', [LoanController::class, 'reject'])->name('loans.reject');
    Route::patch('/loans/{loan}/return', [LoanController::class, 'returnLoan'])->name('loans.return');
    Route::delete('/loans/{loan}', [LoanController::class, 'destroy'])->name('loans.destroy');
    Route::post('/loans/{loan}/feedbacks', [FeedbackController::class, 'store'])->name('feedbacks.store');
    Route::post('/password/update', [AuthController::class, 'updatePassword'])->name('password.update');
});
