<?php

use App\Http\Controllers\admin\HotelController;
use App\Http\Controllers\admin\RoomController;
use App\Http\Controllers\ProfileController;
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
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard.index');
    })->name('dashboard');

    /** Hotel Management routes */
    Route::resource('hotel-management', HotelController::class);
    Route::resource('user-management', \App\Http\Controllers\admin\UserController::class);

    /** Room Management routes */
    Route::get('room-management/{hotel}/create', [RoomController::class, 'create'])->name('room-management.create');
    Route::post('room-management/{hotel}', [RoomController::class, 'store'])->name('room-management.store');


    Route::get('upload-room-images/{room}/create', [RoomController::class, 'uploadImagesCreate'])->name('room-management.upload.images.create');
    Route::get('upload-room-images/{room}/edit', [RoomController::class, 'edit'])->name('room-management.edit');
    Route::put('upload-room-images/{room}/update', [RoomController::class, 'update'])->name('room-management.update');
    Route::post('upload-room-images/{room}', [RoomController::class, 'uploadImages'])->name('room-management.upload.images');
    Route::post('upload-room-images-delete/{room}/{media}', [RoomController::class, 'deleteImages'])->name('room-management.delete.images');

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
