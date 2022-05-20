<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BusinessDetailController;
use App\Http\Controllers\CooperativeController;
use App\Http\Controllers\CourierController;
use App\Http\Controllers\InstallmentController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StashController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [UserController::class, 'register']);
Route::post('login', [UserController::class, 'login']);

// cooperatives public
Route::get('all-coperatives', [CooperativeController::class, 'fetchActiveCooperatives']);
Route::get('cooperative/{id}', [CooperativeController::class, 'fetchActiveCooperatives']);

// products public
Route::get('all-products', [ProductController::class, 'fetchAllProducts']); // testing
Route::get('public-product/{id}', [ProductController::class, 'fetchProduct']); // testing

// couriers
Route::get('couriers', [CourierController::class, 'index']);
Route::get('courier/{id}', [CourierController::class, 'fetch']);

Route::middleware('auth:sanctum')->group(function() {
    // users
    Route::get('user', [UserController::class, 'fetch']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('update', [UserController::class, 'update']);
    
    // update verification cooperative
    Route::post('uvc', [UserController::class, 'updateVerificationCooperative']);

    // cooperative
    Route::get('cooperatives', [CooperativeController::class, 'fetch']);
    Route::post('create-cooperative', [CooperativeController::class, 'create']);
    Route::post('update-cooperative/{id}', [CooperativeController::class, 'update']);
    Route::post('delete-cooperative', [CooperativeController::class, 'delete']);

    // business
    Route::get('business', [BusinessController::class, 'index']);
    Route::get('business/{id}', [BusinessController::class, 'fetch']);
    Route::post('create-business', [BusinessController::class, 'create']);
    Route::post('update-business/{id}', [BusinessController::class, 'update']);
    Route::post('delete-business/{id}', [BusinessController::class, 'delete']);

    // business details
    Route::get('get-business', [BusinessDetailController::class, 'index']);
    Route::get('get-business/{id}', [BusinessDetailController::class, 'fetch']);
    Route::post('cooperative/create-business', [BusinessDetailController::class, 'create']);
    Route::post('cooperative/update-business/{id}', [BusinessDetailController::class, 'update']);
    Route::post('cooperative/delete-business/{id}', [BusinessDetailController::class, 'delete']);

    // product categories
    Route::get('product-categories', [ProductCategoryController::class, 'index']);
    Route::get('product-category/{id}', [ProductCategoryController::class, 'fetch']);
    Route::post('product-category/create', [ProductCategoryController::class, 'create']);
    Route::post('product-category/update/{id}', [ProductCategoryController::class, 'update']);
    Route::post('product-category/delete/{id}', [ProductCategoryController::class, 'delete']);

    // products
    Route::get('products', [ProductController::class, 'index']); // testing
    Route::get('product/{id}', [ProductController::class, 'fetch']); // testing
    Route::post('product/create', [ProductController::class, 'create']); // testing
    Route::post('product/update/{id}', [ProductController::class, 'update']); // testing
    Route::post('product/delete/{id}', [ProductController::class, 'delete']); // testing

    // loans
    Route::get('loans', [LoanController::class, 'index']);
    Route::get('loan/{id}', [LoanController::class, 'fetch']);
    Route::post('loan-create', [LoanController::class, 'store']);
    Route::post('loan-update/{id}', [LoanController::class, 'update']);
    Route::post('loan-delete/{id}', [LoanController::class, 'delete']);

    // stash
    Route::get('stashs', [StashController::class, 'index']);
    Route::get('stash/{id}', [StashController::class, 'fetch']);
    Route::post('stash/create', [StashController::class, 'create']);
    Route::post('stash/update/{id}', [StashController::class, 'update']);
    Route::post('stash/delete/{id}', [StashController::class, 'delete']);

    // courier
    Route::post('courier/create', [CourierController::class, 'create']);
    Route::post('courier/update/{id}', [CourierController::class, 'update']);
    Route::post('courier/delete/{id}', [CourierController::class, 'delete']);

    // installment
    Route::get('installments', [InstallmentController::class, 'index']);
    Route::get('installment/{id}', [InstallmentController::class, 'fetch']);
    Route::post('installment/create', [InstallmentController::class, 'create']);
    Route::post('installment/update/{id}', [InstallmentController::class, 'update']);
    Route::post('installment/delete/{id}', [InstallmentController::class, 'delete']);

    // transaction
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::get('transaction/{id}', [TransactionController::class, 'fetch']);
    Route::post('transaction/create', [TransactionController::class, 'create']);
    Route::post('transaction/update/{id}', [TransactionController::class, 'update']);
    Route::post('transaction/delete/{id}', [TransactionController::class, 'delete']);

});