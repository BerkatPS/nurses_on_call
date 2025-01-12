<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Nurse\EmergencyController;
use App\Http\Controllers\Nurse\NurseBookingController;
use App\Http\Controllers\Nurse\NurseController;
use App\Http\Controllers\Nurse\NurseProfileController;
use App\Http\Controllers\User\UserBookingController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserEmergencyController;
use App\Http\Controllers\User\UserListNurseController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Auth;
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




Route::get('/register', [RegisteredUserController::class, 'create'])
    ->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Login
Route::get('/login', [AuthenticatedSessionController::class, 'showLoginForm'])
    ->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Logout
Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])
    ->name('logout');



// Tambahkan di routes/web.php
Route::middleware(['auth'])->group(function() {

    Route::get('/nurse', [NurseController::class, 'index'])
        ->name('nurse.index');

    Route::get('/nurse/dashboard/performance-chart', [NurseController::class, 'getPerformanceChartData'])
        ->name('nurse.dashboard.performance');


    Route::get('/nurse/dashboard/emergency-distribution', [NurseController::class, 'getEmergencyCallDistribution'])
        ->name('nurse.dashboard.emergency');

    Route::get('/nurse/bookings', [NurseBookingController::class, 'index'])
        ->name('nurse.bookings');

    Route::post('/nurse/bookings/{booking}/confirm', [NurseController::class, 'confirmBooking'])->name('nurse.bookings.confirm');

    Route::get('/nurse/bookings/{bookingId}', [NurseBookingController::class, 'show'])
        ->name('nurse.bookings.show');


    Route::post('/nurse/bookings/{bookingId}/cancel', [NurseBookingController::class, 'cancelBooking'])
        ->name('nurse.bookings.cancel');

    Route::get('/nurse/emergency', [EmergencyController::class, 'index'])
        ->name('nurse.emergency');

    Route::post('/nurse/emergency/{callId}/respond', [EmergencyController::class, 'respondToEmergencyCall'])
        ->name('nurse.emergency.respond');

    Route::post('/nurse/emergency/{callId}/complete', [EmergencyController::class, 'completeEmergencyCall'])
        ->name('nurse.emergency.complete');

    Route::get('/nurse/profile', [NurseProfileController::class, 'index'])
        ->name('nurse.profile');

    Route::post('/nurse/profile/update-status', [NurseProfileController::class, 'updateStatus'])
        ->name('nurse.profile.updateStatus');

    Route::post('/nurse/profile/update', [NurseProfileController::class, 'updateProfile'])
        ->name('nurse.profile.update');


    Route::get('/users', [UserController::class, 'index'])
        ->name('user.index');


    Route::get('/user/booking', [UserBookingController::class, 'index'])
        ->name('user.bookings');

    Route::post('/user/bookings', [UserBookingController::class, 'create'])
        ->name('user.bookings.create');

    Route::get('/user/bookings/{bookingId}', [UserBookingController::class, 'show'])
    ->name('user.bookings.show');

    Route::put('/user/bookings/{bookingId}/cancel', [UserBookingController::class, 'cancel'])->name('user.bookings.cancel');

    Route::get('/user/emergency-calls', [UserEmergencyController::class, 'index'])->name('user.emergency');

    Route::post('/user/emergency-calls', [UserEmergencyController::class, 'createEmergencyCall'])
        ->name('user.emergency.create');

    // Rute untuk membatalkan panggilan darurat
    Route::post('/user/emergency-calls/{id}/cancel', [UserEmergencyController::class, 'cancelEmergencyCall'])->name('user.emergency.cancel');

    // Rute untuk mendapatkan detail panggilan darurat
    Route::get('/user/emergency-calls/{id}', [UserEmergencyController::class, 'getEmergencyCallDetail'])->name('user.emergency.detail');

    Route::get('/user/profile', [UserProfileController::class, 'index'])->name('user.profile');

    // Rute untuk memperbarui profil
    Route::post('/user/profile', [UserProfileController::class, 'update'])->name('user.profile.update');

    // Rute untuk mengubah password
    Route::post('/user/profile/change-password', [UserProfileController::class, 'changePassword'])->name('user.profile.changePassword');

    // Rute untuk mengunggah avatar
    Route::post('/user/profile/upload-avatar', [UserProfileController::class, 'uploadAvatar'])->name('user.profile.uploadAvatar');


    Route::get('/user/nurses', [UserListNurseController::class, 'index'])
        ->name('user.nurses');

    Route::get('/user/nurse', [UserListNurseController::class, 'getNursesByServiceType'])
        ->name('user.nurses.getNursesByServiceType');
    Route::get('/user/nurses/{id}', [UserListNurseController::class, 'show'])
        ->name('user.nurses.show');




});

// Fallback Route
Route::fallback(function () {
    return view('errors.404');
});
