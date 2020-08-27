<?php
Route::redirect('/', '/admin/home');

Auth::routes(['register' => false]);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('electrician', 'Admin\ElectricianController');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy');
    Route::resource('roles', 'Admin\RolesController');
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');
    Route::resource('users', 'Admin\UsersController');
    Route::resource('dealers', 'Admin\DealersController');
    Route::post('products/add_units/{product}', 'Admin\ProductsController@addUnits')->name('products.add_units');
    Route::post('products/print_code/{product}', 'Admin\ProductsController@printCode')->name('products.print_code');
    Route::post('products/store_units', 'Admin\ProductsController@store_units')->name('products.store_units');
    Route::resource('products', 'Admin\ProductsController');
    Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy');
	Route::get('rewards/check', 'Admin\RewardsController@check')->name('rewards.check');
	Route::get('rewards/bulk', 'Admin\RewardsController@bulkAdd')->name('rewards.bulk');
	Route::post('rewards/bulk', 'Admin\RewardsController@bulkStore')->name('rewards.bulk');
	Route::resource('rewards', 'Admin\RewardsController');
	Route::get('payments/generate', 'Admin\PaymentsController@generate')->name('payments.generate');
	Route::get('payments/requests', 'Admin\PaymentsController@checkRequests')->name('payments.requests');
	Route::resource('payments', 'Admin\PaymentsController');
	Route::get('points', 'Admin\RewardsController@getPoints');
	Route::get('reports/products', 'Admin\ReportsController@getProducts')->name('reports.products');
	Route::get('reports/electrician', 'Admin\ReportsController@getElectrician')->name('reports.electrician');
	Route::get('reports/dealers', 'Admin\ReportsController@getDealers')->name('reports.dealers');
	Route::get('ele', 'Admin\ElectricianController@converter');
});
