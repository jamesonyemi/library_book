<?php

use App\Models\Book;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\CheckInBookController;
use App\Http\Controllers\CheckoutBookController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", function () {
    return response()->json("test");
});

Route::get("/login", function () {
    return response()->json("Welcome to..");
})->name("login");

Route::post('/authors', [AuthorController::class, 'store'])->name('authors.store');
Route::post('/checkout/{book}', [CheckoutBookController::class, 'store'])->name('checkout');
Route::post('/checkin/{book}', [CheckInBookController::class, 'store'])->name('checkin');

Route::resource('/books', BookController::class);
