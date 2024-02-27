<?php

use App\Http\Controllers\admin\HotelController;
use App\Http\Controllers\admin\RezervationManagementController;
use App\Http\Controllers\admin\RoomController;
use App\Http\Controllers\ProfileController;
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


Route::post('/fail-payment',[App\Http\Controllers\PaymentStatusController::class,'failed']);
Route::post('/success-payment',[App\Http\Controllers\PaymentStatusController::class,'success']);

Route::get('havale', function () {

    $res = \App\Models\Reservation::query()->where('payment_method', 'bank_transfer')->where('payment_status', true)->get();

    foreach ($res as $item) {

    }


    $test = new \App\Services\BankTransferCheckService();
    $test->check();



});



Route::get('/', function () {
    if (Auth::check() && Auth::user()->role == 'ADMIN') {
        return redirect()->route('dashboard');
    }

    if (Auth::check() && Auth::user()->role == 'WORKER') {
        return redirect()->route('reservation.index');
    }

    if (Auth::check() && Auth::user()->role == 'USER') {
        return redirect()->route('user-dashboard');
    }

    return redirect()->route('login');
});

Route::middleware(['auth', 'dashboard'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\admin\DashboardController::class, 'index'])->name('dashboard');
});


Route::middleware(['auth', 'admin'])->group(function () {

    Route::get('/import-file',[\App\Http\Controllers\admin\UserController::class,'importFile'])->name('import.file.page');
    Route::get('/download--example-file-user',[\App\Http\Controllers\admin\UserController::class,'exampleDownload'])
         ->name
    ('example.file.download');
    Route::post('/import-excel-file-user',[\App\Http\Controllers\admin\UserController::class,'importDownload'])->name
    ('import.file');


    Route::get('/settings', [\App\Http\Controllers\admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\admin\SettingsController::class, 'update'])->name('settings.update');

    /** Hotel Management routes */
    Route::resource('hotel-management', HotelController::class);
    Route::resource('user', \App\Http\Controllers\admin\UserController::class);
    Route::resource('reservation', RezervationManagementController::class);

    /** Room Management routes */
    Route::get('room-management/{hotel}/create', [RoomController::class, 'create'])->name('room-management.create');
    Route::post('room-management/{hotel}', [RoomController::class, 'store'])->name('room-management.store');
    Route::get('reservation-management/manuel-reservation-page/{room}',
               [\App\Http\Controllers\user\RezervationController::class, 'manuelCreate'])->name
    ('reservation-management.manuel.create');


    Route::get('upload-room-images/{room}/create', [RoomController::class, 'uploadImagesCreate'])->name('room-management.upload.images.create');
    Route::get('upload-room-images/{room}/edit', [RoomController::class, 'edit'])->name('room-management.edit');
    Route::put('upload-room-images/{room}/update', [RoomController::class, 'update'])->name('room-management.update');
    Route::post('upload-room-images/{room}', [RoomController::class, 'uploadImages'])->name('room-management.upload.images');
    Route::post('upload-room-images-delete/{room}/{media}', [RoomController::class, 'deleteImages'])->name('room-management.delete.images');

});

Route::middleware(['auth', 'user'])->group(function () {
    Route::get('/user-dashboard', [\App\Http\Controllers\user\DashboardController::class, 'index'])->name('user-dashboard');
    Route::get('/user-reservation/{hotel}', [\App\Http\Controllers\user\RezervationController::class, 'index'])->name
    ('user-reservation');
    Route::get('/user-reservation/detail/{room}', [\App\Http\Controllers\user\RezervationController::class, 'show'])
         ->name
    ('user-reservation.show');

    Route::get('/user-reservation/room/detail/{room}', [\App\Http\Controllers\user\RezervationController::class, 'showRoom'])
         ->name
         ('user-reservation.showRoom');

    Route::get('/reservation-management/create/{room}', [\App\Http\Controllers\user\RezervationController::class,
                                                             'createReservation'])
         ->name
         ('user-reservation.createReservation');

    Route::get('/my-reservations', [\App\Http\Controllers\user\RezervationController::class,
                                                         'myReservations'])
         ->name
         ('user-reservation.myReservations');

    Route::get('/my-reservations/{reservation}', [\App\Http\Controllers\user\RezervationController::class,
                                    'myReservationDetail'])
         ->name
         ('user-reservation.myReservations.detail');

    Route::delete('/my-reservations/{reservation}', [\App\Http\Controllers\user\RezervationController::class,
                                                  'requestCancelReservation'])
         ->name
         ('user-reservation.myReservations.requestCancelReservation');


});

Route::middleware('auth')
     ->group(function () {
         Route::get('/profile', [
             ProfileController::class,
             'edit'
         ])
              ->name('profile.edit');
         Route::patch('/profile', [
             ProfileController::class,
             'update'
         ])
              ->name('profile.update');
         Route::delete('/profile', [
             ProfileController::class,
             'destroy'
         ])
              ->name('profile.destroy');
     });


require __DIR__ . '/auth.php';
