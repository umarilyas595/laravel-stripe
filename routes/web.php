<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/purchase', [PurchaseController::class, 'index'])->name('purchase.index');
Route::post('/purchase', [PurchaseController::class, 'process'])->name('purchase.process');




Route::middleware('auth')->group(function () {
    Route::get('/purchase/pay', [PurchaseController::class, 'pay'])->name('purchase.pay');
    Route::post('/purchase/process-payment', [PurchaseController::class, 'processPayment'])->name('purchase.process.payment');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';