<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceLocationApiController;
use App\Http\Controllers\AttendanceLocationController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/checkin');

Route::get('/checkin', function () {
    return view('checkin');
})->middleware('auth')->name('checkin');

// Route::get('/users', function () {
//     return view('users');
// })->middleware('auth')->name('dashboard');
// ->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

use App\Http\Controllers\UserController;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::resource('attendance-location', AttendanceLocationController::class);
    Route::resource('holidays', HolidayController::class);
});

Route::get('/api/attendance-locations', [AttendanceLocationApiController::class, 'index']);
// Butuh login aja
Route::post('/api/attendance/checkin', [AttendanceController::class, 'checkIn'])
    ->middleware('auth');

Route::post('/api/attendance/checkout', [AttendanceController::class, 'checkOut'])
    ->middleware('auth');

// List absensi semua user → Hanya admin
Route::get('/attendances', [AttendanceController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('attendances.index');

// Riwayat absen user tertentu → hanya admin atau user sendiri
Route::get('/attendances/user/{userId}', [AttendanceController::class, 'userAttendance'])
    ->middleware('auth')
    ->name('attendances.user');

// });

require __DIR__.'/auth.php';
