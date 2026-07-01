<?php

use App\Http\Controllers\Admin\AdminAppointmentController;
use App\Http\Controllers\Admin\ServiceAdminController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('services.index'));

// Autenticação
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Área autenticada (cliente)
Route::middleware('auth')->group(function () {
    Route::get('/servicos', [ServiceController::class, 'index'])->name('services.index');
    Route::get('/servicos/{service}', [ServiceController::class, 'show'])->name('services.show');

    Route::get('/agendar/horarios', [AppointmentController::class, 'slots'])->name('appointments.slots');
    Route::post('/agendamentos', [AppointmentController::class, 'store'])->name('appointments.store');
    Route::get('/meus-agendamentos', [AppointmentController::class, 'index'])->name('appointments.index');
    Route::patch('/agendamentos/{appointment}/cancelar', [AppointmentController::class, 'cancel'])
        ->name('appointments.cancel');
});

// Área administrativa
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/agenda', [AdminAppointmentController::class, 'index'])->name('appointments.index');
    Route::resource('services', ServiceAdminController::class)->except(['show']);
});
