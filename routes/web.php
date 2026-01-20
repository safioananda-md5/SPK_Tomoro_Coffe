<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(Route('login'));
});

Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'store'])->name('store.login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Routes
Route::group([
    'prefix' => '/admin',
    'as' => 'admin.',
    'middleware' => ['auth', 'role:admin', 'decrypt:id']
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
