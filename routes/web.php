<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RankingController;
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

    Route::group([
        'prefix' => '/kriteria',
        'as' => 'kriteria.',
    ], function () {
        Route::get('/', [CriteriaController::class, 'index'])->name('index');
        Route::get('/tambah-kriteria', [CriteriaController::class, 'create'])->name('create');
        Route::post('/tambah-kriteria', [CriteriaController::class, 'store'])->name('store');
        Route::get('/{id}/edit-kriteria', [CriteriaController::class, 'edit'])->name('edit');
        Route::put('/{id}/edit-kriteria', [CriteriaController::class, 'update'])->name('update');
        Route::delete('/{id}/hapus-kriteria', [CriteriaController::class, 'delete'])->name('delete');
    });

    Route::group([
        'prefix' => '/alternatif',
        'as' => 'alternatif.',
    ], function () {
        Route::get('/', [AlternatifController::class, 'index'])->name('index');
        Route::get('/tambah-alternatif', [AlternatifController::class, 'create'])->name('create');
        Route::post('/tambah-alternatif', [AlternatifController::class, 'store'])->name('store');
        Route::delete('/{id}/hapus-alternatif', [AlternatifController::class, 'delete'])->name('delete');
        Route::delete('/hapus-seluruh-alternatif', [AlternatifController::class, 'alldelete'])->name('alldelete');
    });

    Route::group([
        'prefix' => '/perangkingan',
        'as' => 'perangkingan.',
    ], function () {
        Route::get('/', [RankingController::class, 'index'])->name('index');
        Route::get('/nilai-utility', [RankingController::class, 'utility'])->name('utility');
        Route::get('/bobot-utility', [RankingController::class, 'bobotutility'])->name('bobotutility');
        Route::get('/nilai-akhir', [RankingController::class, 'nilaiakhir'])->name('nilaiakhir');
    });
});

// Owner route

Route::group([
    'prefix' => '/owner',
    'as' => 'owner.',
    'middleware' => ['auth', 'role:owner', 'decrypt:id']
], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
