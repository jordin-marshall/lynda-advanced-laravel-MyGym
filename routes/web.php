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
    //Route::get('/member/dashboard', function () { return view('member.dashboard');})->middleware(['role:member'])->name('member.dashboard');

    Route::resource('/instructor/schedule', ScheduledClassController::class)->only(['index', 'create', 'store', 'destroy'])->middleware(['role:instructor']);

    /* Member routes */
    Route::middleware(['role:member'])->group(function () {
        Route::get('/member/dashboard', function () {
            return view('member.dashboard');
        })->name('member.dashboard');
        Route::get('/member/book', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/member/bookings', [BookingController::class, 'store'])->name('booking.store');
        Route::get('/member/bookings', [BookingController::class, 'index'])->name('booking.index');
        Route::delete('/member/bookings/{id}', [BookingController::class, 'destroy'])->name('booking.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
