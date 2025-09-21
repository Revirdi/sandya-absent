<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AttendanceLocationApiController;
use App\Http\Controllers\AttendanceLocationController;
use App\Http\Controllers\GlobalSettingController;
use App\Http\Controllers\HolidayController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/checkin');

Route::get('/checkin', [AttendanceController::class, 'showCheckin'])
    ->middleware('auth')
    ->name('checkin');

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
Route::get('/attendance-locations/compare', [AttendanceLocationController::class, 'compareView'])
    ->name('attendance.locations.compare.view');
Route::get('/api/locations', [AttendanceLocationApiController::class, 'getLocations']);
Route::get('/api/compare', [AttendanceLocationApiController::class, 'compare'])->name('compare');

Route::post('/api/attendance/checkin', [AttendanceController::class, 'checkIn'])
    ->middleware('auth');

Route::post('/api/attendance/checkout', [AttendanceController::class, 'checkOut'])
    ->middleware('auth');

Route::get('/attendances', [AttendanceController::class, 'index'])
    ->middleware(['auth', 'role:admin'])
    ->name('attendances.index');

Route::get('/attendances/user', [AttendanceController::class, 'userAttendance'])
    ->middleware('auth')
    ->name('attendances.user');

Route::get('/attendances/edit/{userId}', [AttendanceController::class, 'edit'])
    ->middleware(['auth', 'role:admin'])
    ->name('attendances.edit');

Route::delete('/attendances/{id}', [AttendanceController::class, 'destroy'])
    ->middleware(['auth', 'role:admin'])
    ->name('attendances.destroy');

Route::put('/attendances/{attendance}', [AttendanceController::class, 'update'])
    ->middleware(['auth', 'role:admin'])
    ->name('attendances.update');

Route::get('/attendance/pdf', [AttendanceController::class, 'exportPdf'])->name('attendance.pdf');

Route::get('/attendances/create', [AttendanceController::class, 'create'])
    ->middleware(['auth', 'role:admin'])
    ->name('attendances.create');

Route::post('/attendances', [AttendanceController::class, 'store'])
    ->middleware(['auth', 'role:admin'])
    ->name('attendances.store');

Route::resource('globals', GlobalSettingController::class)->except(['show']);
Route::get('/api/globals/{name}', [GlobalSettingController::class, 'getByName'])->name('globals.getByName');


require __DIR__.'/auth.php';
