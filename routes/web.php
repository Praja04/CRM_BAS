<?php
use App\Http\Controllers\DashboardController;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;


Route::get('/', function () {
    return view('signin/login');
});
Route::get('layout', function () {
    return view('layout');
});

// Route Group untuk autentikasi
Route::middleware('guest')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Route untuk halaman utama CRUD
Route::get('users', [UserController::class, 'index'])->name('users.index');
Route::get('send', [UserController::class, 'sendEmail'])->name('users.send');

// Route untuk operasi CRUD lainnya
Route::get('users-data', [UserController::class, 'getUsers'])->name('users.data');
Route::post('users', [UserController::class, 'store'])->name('users.store');
Route::get('users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


Route::get('customers', [CustomerController::class, 'index'])->name('customers');
Route::get('customers/data', [CustomerController::class, 'getData'])->name('customers.data');
Route::post('customers', [CustomerController::class, 'store'])->name('customers.store');
Route::put('/customers/{userId}/update-status', [CustomerController::class, 'updateStatus']);
Route::get('customers/{userId}/edit', [CustomerController::class, 'edit']);
Route::put('customers/{userId}', [CustomerController::class, 'update']);
Route::delete('customers/{userId}', [CustomerController::class, 'destroy']);



Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/dashboard/data', [DashboardController::class, 'getCustomerStatusData'])->name('dashboard.data');
