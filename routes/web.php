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

Route::group(['middleware' => 'auth.shopify'], function () {
    Route::get('/', 'HomeController@index');
});

/*Route::get('/', function () {
    return view('welcome');
})->middleware(['auth.shopify'])->name('home');*/

Route::get('/shop', function () {
    return view('welcome');
})->middleware(['auth.shopify'])->name('shop');

Route::get('/login', function () {
    return view('login');
});


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

// Success text update
Route::post('/settings/success-text', 'DiscountCodeController@updateSuccessText');

// Failure text update
Route::post('/settings/failure-text', 'DiscountCodeController@updateFailureText');

// API for Storefront
Route::get('/api/button-display/', 'DiscountCodeController@showVerifyButton');

Route::get('/verify-student/{verification}', 'DiscountCodeController@verifyStudent');

Route::get('/api/get-discount-code', 'DiscountCodeController@getDiscountCode');
Route::post('/api/get-discount-code', 'DiscountCodeController@getDiscountCode');

// Install steps
Route::get('/app-install-steps', 'InstallationController@index');


// Uninstalled webhook
Route::get('/api/app-uninstall', 'WebhookController@appUninstalledWebhook');
Route::post('/api/app-uninstall', 'WebhookController@appUninstalledWebhook');

//Mandatory Webhooks
Route::get('/api/cust-data-erasure', 'WebhookController@customersDelete');
Route::get('/api/cust-data-request', 'WebhookController@customersRequest');
Route::get('/api/shop-data-erasure', 'WebhookController@shopDelete');
