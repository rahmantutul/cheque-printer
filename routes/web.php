<?php

declare(strict_types=1);

use App\Http\Controllers\Admin;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::middleware(['auth', 'active'])->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->middleware(['auth', 'admin', 'active'])->name('admin.')->group(function (): void {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', Admin\UserController::class)->except(['create', 'store', 'show']);
    Route::resource('banks', Admin\BankController::class);
    Route::post('banks/{bank}/default', [Admin\BankController::class, 'toggleDefault'])->name('banks.toggle-default');
});

Route::prefix('user')->middleware(['auth', 'active'])->name('user.')->group(function (): void {
    Route::get('/dashboard', [User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/banks', [User\BankController::class, 'index'])->name('banks.index');
    Route::get('/banks/{bank}', [User\BankController::class, 'show'])->name('banks.show');
    Route::resource('templates', User\TemplateController::class);
    Route::get('/cheques/amount-in-words', [User\ChequeController::class, 'amountInWords'])->name('cheques.amount-in-words');
    Route::resource('cheques', User\ChequeController::class);
    Route::post('/cheques/{cheque}/mark-printed', [User\ChequeController::class, 'markPrinted'])->name('cheques.mark-printed');
    Route::get('/cheques/{cheque}/print', [User\ChequeController::class, 'print'])->name('cheques.print');
    Route::get('/cheques/{cheque}/pdf', [User\ChequeController::class, 'pdf'])->name('cheques.pdf');
    Route::get('/settings', [User\SettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [User\SettingController::class, 'update'])->name('settings.update');
});

require __DIR__.'/auth.php';
