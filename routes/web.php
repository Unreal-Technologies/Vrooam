<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductsController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::resource('cart', CartController::class)
    -> only(['index', 'store', 'update', 'destroy'])
    -> parameters([
        'id' => 'id'
    ])
    -> middleware(['auth', 'verified']);

Route::resource('products', ProductsController::class)
    -> only(['index', 'store'])
    -> middleware(['auth', 'verified']);

Route::resource('product', ProductController::class)
    -> only(['index', 'store'])
    -> middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
