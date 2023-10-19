<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduledClassController;
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

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->middleware(['auth'])->name('dashboard');
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->middleware(['role:admin'])->name('admin.dashboard');
    Route::get('/instructor/dashboard', function () {
        return view('instructor.dashboard');
    })->middleware(['role:instructor'])->name('instructor.dashboard');
    //Route::get('/student/dashboard', function () { return view('student.dashboard');})->middleware(['role:student'])->name('student.dashboard');

    Route::resource('/instructor/schedule', ScheduledClassController::class)->only(['index', 'create', 'store', 'destroy'])->middleware(['role:instructor']);

    /* Student routes */
    Route::middleware(['role:student'])->group(function () {
        Route::get('/student/dashboard', function () {
            return view('student.dashboard');
        })->name('student.dashboard');
        Route::get('/student/book', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/student/bookings', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/student/bookings', [BookingController::class, 'index'])->name('booking.index');
        Route::delete('/student/bookings/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
