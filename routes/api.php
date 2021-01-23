<?php

Route::group([ 'prefix' => 'auth'], function (){
	Route::group(['middleware' => ['guest:api']], function () {
		Route::post('login', 'API\AuthController@login');
		//Route::post('signup', 'API\AuthController@signup');
	});
	Route::group(['middleware' => 'auth:api'], function() {
		Route::get('logout', 'API\AuthController@logout');
		Route::get('products', 'Admin\ProductsController@find');
		Route::get('info', 'API\AuthController@profile');
		Route::post('refresh', 'API\AuthController@refresh');
	});
});
Route::group(['middleware' => 'auth:api'], function() {
	Route::get('products', 'API\CommonApiController@findProducts');
});

Route::get('getarea','API\HelperApiController@getAreas');
Route::get('getproducts','API\HelperApiController@getProducts');
Route::get('getbank','API\HelperApiController@getBank');
Route::get('getdealer','API\HelperApiController@getDealer');
Route::get('getbrands','API\HelperApiController@getBrands');
Route::get('getstores','API\HelperApiController@getStores');
Route::get('getstoreswithdue','API\HelperApiController@getStoresWithDue');


Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
});
