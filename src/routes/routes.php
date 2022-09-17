<?php


Route::group(['middleware' => ['web', 'auth']], function() {

	//Route::get('tenant/test', 'Rutatiina\Tenant\Http\Controllers\TenantController@test')->name('tenant.test');
	//Route::get('tenant/fix-settings', 'Rutatiina\Tenant\Http\Controllers\TenantController@fixSettings');

	Route::get('tenant', 'Rutatiina\Tenant\Http\Controllers\TenantController@tenant')->name('tenant');

	Route::get('settings/organisations/{id}/switch', 'Rutatiina\Tenant\Http\Controllers\TenantController@switch')->name('organisations.switch');
	Route::delete('settings/organisations/{id}/delete-transactions', 'Rutatiina\Tenant\Http\Controllers\TenantController@deleteTxns')->name('organisations.delete-transactions');

    Route::resource('settings/organisations/payment-details', 'Rutatiina\Tenant\Http\Controllers\TenantPaymentDetailsController');
    Route::resource('settings/organisations', 'Rutatiina\Tenant\Http\Controllers\TenantController');

});
