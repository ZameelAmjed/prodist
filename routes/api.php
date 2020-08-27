<?php

Route::group([ 'prefix' => 'auth'], function (){
	Route::group(['middleware' => ['guest:api']], function () {
		Route::post('login', 'API\AuthController@login');
		Route::post('signup', 'API\AuthController@signup');
	});
	Route::group(['middleware' => 'auth:api'], function() {
		Route::get('logout', 'API\AuthController@logout');
		Route::post('profile', 'API\ElectricianApiController@getElectrician');
		Route::put('profile', 'API\ElectricianApiController@edit');
		Route::post('addproduct', 'API\ElectricianApiController@addPoints');
		Route::get('myrewards', 'API\ElectricianApiController@getProducts');

	});
});
Route::get('getarea','API\HelperApiController@getAreas');
Route::get('getproducts','API\HelperApiController@getProducts');
Route::get('getbank','API\HelperApiController@getBank');
Route::get('getdealer','API\HelperApiController@getDealer');

Route::group(['prefix' => '/v1', 'namespace' => 'Api\V1', 'as' => 'api.'], function () {
});
