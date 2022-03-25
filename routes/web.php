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
Route::get('/inventory/stockBalance', 'InventoryController@index');
Route::post('/inventory/delete/{id}', 'InventoryController@delete');
Route::get('/inventory/{id}', 'InventoryController@detail');
Route::post('/inventory/{id}', 'InventoryController@updateDb');
