<?php

use Illuminate\Http\Request;

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

Route::get('products', 'ProductController@getProducts');

Route::get('products/{product_id}', 'ProductController@getProduct');

Route::post('product', 'ProductController@createProduct');

Route::put('product/{product_id}', 'ProductController@updateProduct');

Route::delete('product/{product_id}', 'ProductController@deleteProduct');
