<?php

//use App\Http\Controllers\NotaController;
//use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/nota', [NotaController::class, 'index'])->name('nota.index');
// Route::get('/nota/create', [NotaController::class, 'create'])->name('nota.create');
// Route::post('/nota', [NotaController::class, 'store'])->name('nota.store');
// Route::get('/nota/{id}', [NotaController::class, 'show'])->name('nota.show');
// Route::get('/nota/{id}/edit', [NotaController::class, 'edit'])->name('nota.edit');
// Route::put('/nota/{id}', [NotaController::class, 'update'])->name('nota.update');
// Route::delete('/nota/{id}', [NotaController::class, 'destroy'])->name('nota.destroy');

//Route::resource('nota', NotaController::class);