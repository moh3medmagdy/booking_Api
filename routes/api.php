<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Booking\BookingController;
use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Controllers\Api\Event\EventController;
use App\Http\Middleware\CheckRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//register
Route::post('register',[AuthController::class,'register']);
Route::post('verify-email', [AuthController::class, 'verifyEmail']);

//login
Route::post('login',[AuthController::class,'login']);
// Route::post('verify-otp', [AuthController::class, 'verifyOtp']);

//logout
Route::post('logout',[AuthController::class,'logout'])->middleware('auth:sanctum');

//category routes         //middleware
Route::middleware(["auth:sanctum","role:admin"])->group(function () {
    Route::post('category.store',[CategoryController::class,'store']);
    Route::post('category.update/{id}',[CategoryController::class,'update']);
    Route::delete('category.delete/{id}',[CategoryController::class,'destroy']);

    Route::post('event.store',[EventController::class,'store']);
    Route::post('event.update/{id}',[EventController::class,'update']);
    Route::delete('event.delete/{id}',[EventController::class,'destroy']);
});


Route::get('category.index',[CategoryController::class,'index']);
Route::get('category.show/{id}',[CategoryController::class,'show']);

//Event Route
Route::get('event.index',[EventController::class,'index']);
Route::get('event.show/{id}',[EventController::class,'show']);

//Booking
Route::Post('book.store/{eventId}',[BookingController::class,'book'])->middleware('auth:sanctum');
Route::get('myBooking',[BookingController::class,'myBooking'])->middleware('auth:sanctum');
Route::post('cancelBooking/{id}',[BookingController::class,'cancel'])->middleware('auth:sanctum');
