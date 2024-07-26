<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('events', EventController::class);

Route::get('tickets/purchase', [TicketController::class, 'showPurchaseForm'])->name('tickets.purchase.form');
Route::post('tickets/purchase', [TicketController::class, 'purchase'])->name('tickets.purchase');
Route::get('tickets/payment', [TicketController::class, 'showPaymentPage'])->name('tickets.payment');
Route::resource('tickets', TicketController::class)->middleware('auth');
Route::post('tickets/payment/process', [TicketController::class, 'processPayment'])->name('tickets.payment.process');
Route::post('tickets/payment/callback', [TicketController::class, 'handleCallback'])->name('tickets.payment.callback');




Route::get('register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegistrationController::class, 'register']);

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::get('/contact', [ContactController::class, 'showForm'])->name('contact.form');
Route::post('/contact', [ContactController::class, 'submitForm'])->name('contact.submit');

require __DIR__.'/auth.php';
