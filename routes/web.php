<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RunCommandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
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

Route::get('/run-command', [RunCommandController::class,'runCommand'])->name('run-command');
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('post-login', [LoginController::class, 'postLogin'])->name('login.post');

Route::get('register', [LoginController::class, 'register'])->name('register');
Route::post('post-register', [LoginController::class, 'postRegister'])->name('register.post');

//Route::get('scraper',[RunCommandController::class,'scraper']);
Route::resource('category', CategoryController::class);
Route::get('product', [ProductController::class,'getList'])->name('list');
Route::get('search',[ProductController::class,'search'])->name('search');
Route::get('/product/{id}',[ProductController::class,'viewproduct'])->name('viewproduct');
Route::post('find',[ProductController::class,'find'])->name('find');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/', [ProductController::class, 'getList'])->name('list');
