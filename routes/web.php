<?php

use App\Http\Controllers\AttendanceLocationApiController;
use App\Http\Controllers\AttendanceLocationController;
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
});

Route::resource('attendance-location', AttendanceLocationController::class);
Route::get('/api/attendance-locations', [AttendanceLocationApiController::class, 'index']);
// });

require __DIR__.'/auth.php';
