<?php

use function Illuminate\Support\Facades\middleware;

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

Route::get('/', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/salehistory/report', 'SaleHistoryController@report');
Route::post('/salehistory/{productId}', 'SaleHistoryController@sale');

Route::get('/product', 'ProductController@listt');
Route::post('/product', 'ProductController@create');
Route::put('/product/{productId}', 'ProductController@update');
Route::delete('/product/{productId}', 'ProductController@delete');

Route::get('/material', 'MaterialController@listt');
Route::post('/material', 'MaterialController@create');
Route::put('/material/{materialId}', 'MaterialController@update');
Route::delete('/material/{materialId}', 'MaterialController@delete');

Route::get('/materialinouthistory/reportinventory', 'MaterialInOutHistoryController@reportInventory');
Route::get('/materialinouthistory/reportinventoryspending', 'MaterialInOutHistoryController@reportInventorySpending');
Route::post('/materialinouthistory/in', 'MaterialInOutHistoryController@createIn');
Route::post('/materialinouthistory/out', 'MaterialInOutHistoryController@createOut');

Route::get('/orderobject', 'OrderObjectController@listt');
Route::post('/orderobject', 'OrderObjectController@create');
Route::put('/orderobject/{orderObjectId}', 'OrderObjectController@update');
Route::delete('/orderobject/{orderObjectId}', 'OrderObjectController@delete');

Route::get('/order/list', 'OrderController@listt');
Route::get('/order/{orderId}', 'OrderController@show');
Route::get('/order/{orderId}/orderhistory', 'OrderController@initOrderHistory');
Route::get('/order/{orderId}/orderhistory/review', 'OrderController@reviewOrderHistory');
Route::get('/order/{orderId}/bill', 'OrderController@bill');
Route::post('/order', 'OrderController@create');
Route::post('/order/quick', 'OrderController@createQuick');
Route::post('/order/{orderId}/pay', 'OrderController@pay');
Route::delete('/order/{orderId}', 'OrderController@delete');

Route::get('/orderhistory/{orderId}/grouped', 'OrderHistoryController@showGroupedList');
Route::post('/orderhistory/{productId}/{orderId}', 'OrderHistoryController@order');

Route::get('/sale', function(){
	return view('sale');
})->middleware('auth');

Route::get('/salereport', function(){
	return view('salereport');
})->middleware('auth');

Route::get('/productview', function(){
	return view('product');
})->middleware('auth');

Route::get('/materialview', function(){
	return view('material');
})->middleware('auth');

Route::get('/materialin', function(){
	return view('materialin');
})->middleware('auth');

Route::get('/materialout', function(){
	return view('materialout');
})->middleware('auth');

Route::get('/materialinventory', function(){
	return view('materialinventory');
})->middleware('auth');

Route::get('/materialinventoryspending', function(){
	return view('materialinventoryspending');
})->middleware('auth');

Route::get('/orderobjectview', function(){
	return view('orderobject');
})->middleware('auth');

Route::get('/order', function(){
	return view('order');
})->middleware('auth');

Route::get('/bill', function(){
	return view('bill');
})->middleware('auth');