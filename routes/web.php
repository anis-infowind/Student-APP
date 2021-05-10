<?php

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

Route::get('/', 'HomeController@index');


/*Route::get('/', function () {
    return view('welcome');
})->middleware(['auth.shopify'])->name('home');

Route::get('/shop', function () {
    return view('welcome');
})->middleware(['auth.shopify'])->name('shop');

Route::get('/login', function () {
    return view('login');
});*/


// Show options
Route::get('/options', 'OptionController@index');


// Discount Codes
Route::get('/discounts', 'DiscountCodeController@index');
Route::get('/discount/create', 'DiscountCodeController@show');
Route::post('/discount/store', 'DiscountCodeController@store');

Route::get('/discount/get-all-collections', 'DiscountCodeController@getAllCollections');
Route::post('/discount/get-all-collections', 'DiscountCodeController@getAllCollections');

Route::get('/discount/get-all-products', 'DiscountCodeController@getAllProducts');
Route::post('/discount/get-all-products', 'DiscountCodeController@getAllProducts');

Route::post('/discount/destroy', 'DiscountCodeController@destroy');
Route::get('/discount/edit/{id}', 'DiscountCodeController@edit');
Route::post('/discount/update', 'DiscountCodeController@update');

Route::get('/settings', 'DiscountCodeController@settings');
Route::post('/settings/update', 'DiscountCodeController@updateSettings');


// Uninstalled webhook
Route::get('/api/app-uninstall', 'WebhookController@appUninstalledWebhook');
Route::post('/api/app-uninstall', 'WebhookController@appUninstalledWebhook');
