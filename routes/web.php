<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;

Route::get('/', [ProductController::class, 'index'])->name('home');

Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.detail');

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

Route::get('/dashboard', function () {
    if (auth()->user()->status !== 'admin') {
        return redirect('/')->with('error', 'Anda tidak memiliki akses ke halaman ini.');
    }
    // return view('dashboard');
    return view('testing.unfinished');
})->middleware(['auth', 'verified'])->name('dashboard');

use App\Http\Controllers\CartController;

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart', [CartController::class, 'store'])->name('cart.store');
Route::patch('/cart', [CartController::class, 'update'])->name('cart.update');

Route::get('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove.delete');

Route::delete('/cart', [CartController::class, 'clear'])->name('cart.clear');

Route::middleware('auth')->group(function () {
    // Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile', function () {
        return view('testing.unfinished');
    })->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
