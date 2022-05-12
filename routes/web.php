<?php

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

Auth::routes();

// Route::get('/', function () {
//     return view('index');
// });

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', 'InventoryController@index');
Route::get('/inventory', 'InventoryController@index');
Route::get('/inventory/create/{type_id}', 'InventoryController@create');
Route::post('/inventory/create/{type_id}', 'InventoryController@createDb');
Route::get('/inventory/stockIn', 'InventoryController@stockIn');
Route::get('/inventory/stockOut', 'InventoryController@stockOut');
Route::post('/inventory/delete/{id}', 'InventoryController@delete');
Route::get('/inventory/update/{id}', 'InventoryController@detail');
Route::post('/inventory/update/{id}', 'InventoryController@updateDb');
Route::get('/inventory/stockBalance', 'InventoryController@index');
Route::get('/inventory/stockBalance/detail/{prouduct_id}/{product_unit}', 'InventoryController@stockBalanceDetail');

Route::get('/products', 'ProductController@index');
Route::get('/products/create', 'ProductController@create');
Route::post('/products/create', 'ProductController@store');
Route::get('/products/{id}', 'ProductController@detail');
Route::post('/products/{id}', 'ProductController@update');
Route::post('/products/delete/{id}', 'ProductController@destroy');

Route::get('/units', 'UnitController@index');
Route::get('/units/create', 'UnitController@create');
Route::post('/units/create', 'UnitController@store');
Route::get('/units/{id}', 'UnitController@detail');
Route::post('/units/{id}', 'UnitController@update');
Route::post('/units/delete/{id}', 'UnitController@destroy');

Route::get('/changePassword', 'HomeController@getChangePassword');
Route::post('/changePassword', 'HomeController@storeChangePw');
