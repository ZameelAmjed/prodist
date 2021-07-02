<?php
Route::redirect('/', '/admin/home');

Auth::routes(['register' => false]);

// Change Password Routes...
Route::get('change_password', 'Auth\ChangePasswordController@showChangePasswordForm')->name('auth.change_password');
Route::patch('change_password', 'Auth\ChangePasswordController@changePassword')->name('auth.change_password');
Route::get('/image/product/{product}','ImageController@showImage');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    Route::resource('permissions', 'Admin\PermissionsController');
    Route::delete('permissions_mass_destroy', 'Admin\PermissionsController@massDestroy')->name('permissions.mass_destroy');
    Route::resource('roles', 'Admin\RolesController');
    Route::delete('roles_mass_destroy', 'Admin\RolesController@massDestroy')->name('roles.mass_destroy');
    Route::resource('users', 'Admin\UsersController');
    Route::resource('products', 'Admin\ProductsController');
    Route::resource('orders', 'Admin\OrderController');
    Route::resource('order_discounts', 'Admin\OrderDiscountsController');
    Route::resource('product_discounts', 'Admin\ProductDiscountsController');
    Route::get('product_discounts/show/{scheme_identifier}', 'Admin\ProductDiscountsController@show');
    Route::get('product_discounts/{scheme_identifier}/edit', 'Admin\ProductDiscountsController@edit');
    Route::post('orders/return', 'Admin\OrderController@newReturn');
    Route::post('orders/free_issue/{order}', 'Admin\OrderController@freeIssue')->name('orders.freeissue');
	//  Route::get('payment/receipt/{payment}', 'Admin\PaymentsController@generateReceipt')->name('payment.receipt');
    Route::get('orders/invoice/{order}', 'Admin\OrderController@getInvoice')->name('orders.invoice');
    Route::resource('suppliers', 'Admin\SuppliersController');
    Route::get('purchase_orders/{purchaseOrder}/grn', 'Admin\PurchaseOrderController@grn')->name('purchase_orders.grn');
    Route::post('purchase_orders/{purchaseOrder}/quickgrn', 'Admin\PurchaseOrderController@quickgrn')->name('purchase_orders.quickgrn');
	Route::resource('purchase_orders', 'Admin\PurchaseOrderController');

	Route::get('payment/returncharges', 'Admin\PaymentController@returnChargeView')
	     ->name('payment.returncharges');
	Route::resource('payment', 'Admin\PaymentController');
	Route::get('payment/receipt/{payment}', 'Admin\PaymentController@receipt')->name('payment.receipt');
	Route::get('payment/returncharges/{miscellaneousCharge}', 'Admin\PaymentController@returnCharge')->name('payment.returncharge');

	Route::delete('users_mass_destroy', 'Admin\UsersController@massDestroy')->name('users.mass_destroy');
	//Stores

    Route::resource('stores', 'Admin\StoresController');
	Route::get('stores/history/{store}','Admin\StoresController@history')->name('stores.history');
	Route::get('reports/get_float_cheque','Admin\ReportsController@getFloatCheque');
	Route::get('reports/get_dishonored_cheque','Admin\ReportsController@getDishonoredCheque');
	//Reports
	Route::get('reports', 'Admin\ReportsController@index')->name('reports.index');
	Route::get('reports/stores', 'Admin\ReportsController@getStores')->name('reports.stores');
	Route::get('reports/inventory', 'Admin\ReportsController@getInventory')->name('reports.inventory');
	Route::get('reports/products', 'Admin\ReportsController@getProducts')->name('reports.products');
	Route::get('reports/payments', 'Admin\ReportsController@getPayments')->name('reports.payments');
	Route::get('reports/payments/due', 'Admin\ReportsController@getDuePayments')->name('reports.duepayments');
	Route::get('reports/orders', 'Admin\ReportsController@getOrders')->name('reports.orders');
	Route::get('reports/purchase_orders', 'Admin\ReportsController@getPurchaseOrders')->name('reports.purchase_orders');
	Route::get('reports/stores_long_due', 'Admin\ReportsController@getStoresDuePayments')->name('reports.stores_long_due');
	Route::get('help', 'HomeController@help')->name('help');

	//image upload
	Route::post('image/upload','ImageController@uploadImage')->name('image.upload');
	Route::patch('image/upload','ImageController@deleteImage')->name('image.upload');
});

