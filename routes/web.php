<?php

use App\Http\Controllers\InvoiceAchiveController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoicesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SectionsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Auth::routes();
//Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::resource('invoices', InvoicesController::class);

Route::resource('sections', SectionsController::class);

Route::resource('products', ProductsController::class);

Route::resource('InvoiceAttachments', InvoiceAttachmentsController::class);

Route::get('/section/{id}', 'App\Http\Controllers\InvoicesController@getproducts');

Route::get('/InvoicesDetails/{id}', 'App\Http\Controllers\InvoicesDetailsController@edit')->name('InvoicesDetails');

Route::get('/View_file/{invoice_number}/{file_name}', 'App\Http\Controllers\InvoicesDetailsController@open_file');

Route::get('/download/{invoice_number}/{file_name}', 'App\Http\Controllers\InvoicesDetailsController@get_file');

Route::post('delete_file', 'App\Http\Controllers\InvoicesDetailsController@destroy')->name('delete_file');

Route::get('/edit_invoice/{id}/', 'App\Http\Controllers\InvoicesController@edit');

Route::get('/Status_show/{id}/', 'App\Http\Controllers\InvoicesController@show')->name('Status_show');

Route::post('/Status_Update/{id}', 'App\Http\Controllers\InvoicesController@Status_Update')->name('Status_Update');

Route::get('/invoices_paid', 'App\Http\Controllers\InvoicesController@showPaidInvoice');

Route::get('/invoices_unpaid', 'App\Http\Controllers\InvoicesController@showUnpaidInvoice');

Route::get('/invoices_Partial', 'App\Http\Controllers\InvoicesController@showPartialInvoice');

Route::get('/Archive_Invoices', 'App\Http\Controllers\InvoiceAchiveController@index');

Route::post('/Archive_upate', 'App\Http\Controllers\InvoiceAchiveController@update')->name('Archive_upate');

Route::post('/Archive_delete', 'App\Http\Controllers\InvoiceAchiveController@destroy')->name('Archive_delete');

Route::get('/Print_invoice/{id}', 'App\Http\Controllers\InvoicesController@Print_invoice');





















Route::get('/{page}', 'App\Http\Controllers\AdminController@index');
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');