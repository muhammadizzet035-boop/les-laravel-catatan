<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\CatatanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Halaman utama
Route::get('/', [BerandaController::class, 'index']);

// Halaman dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/catatan', [CatatanController::class, 'index'])->name('catatan');
    Route::get('/catatan/tambah', [CatatanController::class, 'create']);
    Route::post('/catatan', [CatatanController::class, 'store']);
    Route::get('/catatan/arsip', [CatatanController::class, 'arsip']);
    Route::get('/catatan/export', [CatatanController::class, 'export']);
    Route::get('/catatan/import', [CatatanController::class, 'importForm']);
Route::post('/catatan/import', [CatatanController::class, 'import']);
    

    Route::get('/catatan/{id}', [CatatanController::class, 'show']);
    Route::get('/catatan/{id}/edit', [CatatanController::class, 'edit']);
    Route::put('/catatan/{id}', [CatatanController::class, 'update']);
    Route::delete('/catatan/{id}', [CatatanController::class, 'destroy']);
    Route::post('/catatan/{id}/archive', [CatatanController::class, 'archive']);
    Route::post('/catatan/{id}/unarchive', [CatatanController::class, 'unarchive']);


});
// Auth
require __DIR__ . '/auth.php';
