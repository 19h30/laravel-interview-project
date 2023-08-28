<?php

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

Route::prefix('/admin')->namespace('App\Http\Controllers\Admin')->group(function() {
    Route::match(['get', 'post'], 'login', 'AdminController@login');
    Route::group(['middleware' => ['admin']], function() {
        Route::get('dashboard', 'AdminController@dashboard');
        Route::match(['get', 'post'], 'update-password', 'AdminController@updatePassword');
        Route::match(['get', 'post'], 'update-details', 'AdminController@updateDetails');
        Route::get('logout', 'AdminController@logout');

        // Staffs
        Route::get('staffs', 'AdminController@staffs');
        Route::post('update-staff-status', 'AdminController@updateStaffStatus');
        Route::match(['get', 'post'], 'add-edit-staff/{id?}', 'AdminController@editStaff');
        Route::get('delete-staff/{id}', 'AdminController@deleteStaff');
        Route::match(['get', 'post'], 'update-role/{id}', 'AdminController@updateRole');

        // Category
        Route::get('categories', 'CategoryController@categories');
        Route::post('update-category-status', 'CategoryController@updateCategoryStatus');
        Route::match(['get', 'post'], 'add-category', 'CategoryController@addCategory');

        // Product
        Route::get('products', 'ProductController@products');
        Route::post('update-product-status', 'ProductController@updateProductStatus');
        Route::match(['get', 'post'], 'add-product', 'ProductController@addProduct');

        // Invoice
        Route::get('invoices', 'InvoiceController@invoices');
        Route::get('view-invoice/{id}', 'InvoiceController@viewInvoice');
        // Route::get('download-invoice/{id}', 'InvoiceController@downloadInvoice');
        Route::match(['get', 'post'], 'add-edit-invoice/{id?}', 'InvoiceController@editInvoice');
        Route::get('delete-invoice/{id}', 'InvoiceController@deleteInvoice');
    });
});