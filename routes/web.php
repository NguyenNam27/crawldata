<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProductOriginController;
use App\Http\Controllers\ProductPartnerController;
use App\Http\Controllers\UserController;
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

Route::get('/crawl-data', [PartnerController::class,'crawl'])->name('crawl-data');
Route::get('/run-command', [RunCommandController::class,'runCommand'])->name('run-command');
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('post-register', [AuthController::class, 'postRegister'])->name('register.post');
Route::get('login', [AuthController::class, 'index'])->name('login');
Route::post('post-login', [AuthController::class, 'postLogin'])->name('login.post');

Route::resource('users', UserController::class);

Route::get('scraper',[RunCommandController::class,'scraper']);
//danh muc sp
Route::get('list-category', [CategoryController::class,'listCategory'])->name('list-category');
Route::get('add-category', [CategoryController::class,'addCategory'])->name('add-category');
Route::get('edit-category/{id}', [CategoryController::class,'edit_category'])->name('edit_category');
Route::post('save-category', [CategoryController::class,'saveCategory'])->name('save-category');
Route::post('update-category/{id}', [CategoryController::class,'update_category'])->name('update_category');
Route::get('delete-category/{id}', [CategoryController::class,'delete_category']);
//đối tác
Route::get('list-partner', [PartnerController::class,'listPartner'])->name('list-partner');
Route::get('add-partner', [PartnerController::class,'addPartner'])->name('add-partner');
Route::get('edit-partner/{id}', [PartnerController::class,'edit_partner'])->name('edit_partner');
Route::post('update-partner/{id}', [PartnerController::class,'update_partner'])->name('update_partner');

Route::post('save-partner', [PartnerController::class,'savePartner'])->name('save-partner');
Route::get('delete-partner/{id}', [PartnerController::class,'delete_partner']);
//sản phẩm gốc
Route::get('list-product-original',[ProductOriginController::class,'listProductOriginal'])->name('list-product-original');
Route::get('add-product-original',[ProductOriginController::class,'addProductOriginal'])->name('add-product-original');
Route::get('edit-product-original/{id}',[ProductOriginController::class,'editProductOriginal'])->name('edit-product-original');
Route::post('save-product-original',[ProductOriginController::class,'saveProductOriginal'])->name('save-product-original');
Route::post('update-product-original/{id}',[ProductOriginController::class,'updateProductOriginal'])->name('update-product-original');
Route::get('delete-product-original/{id}',[ProductOriginController::class,'delete_ProductOriginal'])->name('delete-product-original');

//sản phầm đối tác

Route::get('list-product-partner',[ProductPartnerController::class,'listProductPartner'])->name('list-product-partner');

Route::get('compare-price',[ProductPartnerController::class,'comparePrices']);

//Route::resource('partner', PartnerController::class);
//sản phẩm
Route::get('product', [ProductController::class,'getList'])->name('list');
Route::get('search',[ProductController::class,'search'])->name('search');
Route::get('/product/{id}',[ProductController::class,'viewproduct'])->name('viewproduct');
Route::post('find',[ProductController::class,'find'])->name('find');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//Route::get('/', [ProductController::class, 'getList'])->name('list');
