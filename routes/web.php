<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CriteriaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RankingController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(Route('home'));
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
        Route::get('/tambah-edit-alternatif', [AlternatifController::class, 'create'])->name('create');
        Route::post('/tambah-alternatif', [AlternatifController::class, 'store'])->name('store');
        Route::delete('/{id}/hapus-alternatif', [AlternatifController::class, 'delete'])->name('delete');
        Route::delete('/hapus-seluruh-alternatif', [AlternatifController::class, 'alldelete'])->name('alldelete');
    });

    Route::group([
        'prefix' => '/perangkingan',
        'as' => 'perangkingan.',
    ], function () {
        Route::get('/periode', [RankingController::class, 'periode'])->name('periode');
        Route::post('/periode', [RankingController::class, 'post_periode'])->name('post_periode');
        Route::delete('/periode/{id}', [RankingController::class, 'delete_periode'])->name('delete_periode');
        Route::get('/ranking/{id}', [RankingController::class, 'index'])->name('index');
        Route::get('/nilai-utility/{id}', [RankingController::class, 'utility'])->name('utility');
        Route::get('/bobot-utility/{id}', [RankingController::class, 'bobotutility'])->name('bobotutility');
        Route::get('/nilai-akhir/{id}', [RankingController::class, 'nilaiakhir'])->name('nilaiakhir');
    });

    Route::group([
        'prefix' => '/settings',
        'as' => 'settings.',
    ], function () {
        Route::get('/', [SettingsController::class, 'index'])->name('index');
        Route::post('/', [SettingsController::class, 'store'])->name('store');
    });
});

// Owner route

Route::get('/home', [DashboardController::class, 'landing'])->name('home');

// Route::group([
//     'prefix' => '/owner',
//     'as' => 'owner.',
//     'middleware' => ['auth', 'role:owner', 'decrypt:id']
// ], function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
// });
