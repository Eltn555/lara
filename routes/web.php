<?php

use App\Models\Listing;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ListingController;
use App\Models\User;
use Illuminate\Http\Request as HttpRequest;

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

// All Listing
Route::get('/', [ListingController::class, 'index']);

//Show Create Form
Route::get('/listings/create', [ListingController::class, 'create'])->middleware('auth');

//Store Listing Data
Route::post('/listings',
[ListingController::class, 'store']);

//Ajax Search
Route::get('/ajasearch',[ListingController::class,'ajasearch']);

//Show EditForm
Route::get('/listings/{listing}/edit',
[ListingController::class, 'edit'])->middleware('auth');

//Update
Route::put('/listings/{listing}', [ListingController::class,'update'])->middleware('auth');

//Delete
Route::delete('/listings/{listing}', [ListingController::class,'destroy'])->middleware('auth');

//Show menage Form
Route::get('/listings/menage', [ListingController::class, 'menage'])->middleware('auth');

//Single Listing
Route::get('/listings/{listing}',
[ListingController::class, 'show']);

// Show Register CreateForm
Route::get('/register', [UserController::class, 'register'])->middleware('guest');

// Create New User
Route::post('/users', [UserController::class, 'store']);

// Log User Out
Route::post('/logout', [UserController::class, 'logout'])->middleware('auth');

//Show login form
Route::get('/login', [UserController::class, 'login'])->name('login')->middleware('guest');

// Login
Route::post('/users/authenticate', [UserController::class, 'authenticate']);

// Common Resource Routes :
// index - Show all listings
// show - Show single listing
// create - Show form to create new listing
// store - Store new listing
// edit - Show form to edit listing
// update Update listing
// destroy Delete listing |
