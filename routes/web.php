<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\InscricaoController;
use App\Http\Controllers\ProfileController;

Route::get('/', [CursoController::class, 'index'])->name('home');

Route::get('inscricoes/create/{curso}', [InscricaoController::class, 'create'])->name('inscricoes.create');
Route::post('inscricoes/store/{curso}', [InscricaoController::class, 'store'])->name('inscricoes.store');

Route::get('/cursos/{curso}/material/download', [CursoController::class, 'downloadMaterial'])
    ->middleware('auth') 
    ->name('cursos.material.download');

Route::prefix('admin')
     ->middleware(['auth', /* 'isAdmin' */]) 
     ->name('admin.')
     ->group(function() {

    Route::get('cursos', [CursoController::class, 'adminIndex'])->name('cursos.index');
    Route::resource('cursos', CursoController::class)->except(['index', 'show']);

    Route::get('inscricoes', [InscricaoController::class, 'adminIndex'])->name('inscricoes.index');
    Route::get('inscricoes/{inscricao}/edit', [InscricaoController::class, 'edit'])->name('inscricoes.edit');
    Route::put('inscricoes/{inscricao}', [InscricaoController::class, 'update'])->name('inscricoes.update');
    Route::delete('inscricoes/{inscricao}', [InscricaoController::class, 'destroy'])->name('inscricoes.destroy');

    Route::get('inscricoes/export/pdf', [InscricaoController::class, 'exportPdf'])->name('inscricoes.export.pdf');
    Route::get('inscricoes/export/excel', [InscricaoController::class, 'exportExcel'])->name('inscricoes.export.excel');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__.'/auth.php';

