<?php

use Illuminate\Http\Request;
Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::post('validateLogin', 'UserController@validateLogin');
Route::group(['middleware' => 'auth:api'], function(){
	Route::post('details', 'UserController@details');

// LOGOUT
	Route::post('logout', 'UserController@logout');

// CLIENT
	Route::post('addClient', 'clientController@addClient');
	Route::get('client', 'clientController@getClient');
	Route::get('client/{id}/edit', 'clientController@editClient');
	Route::post('client/{id}/update', 'clientController@updateClient');
	Route::delete('client/{id}/delete', 'clientController@deleteClient');

// STAFFS
	Route::post('addStaff', 'staffController@addStaff');
	Route::get('staff', 'staffController@getStaff');
	Route::get('staff/{id}/edit', 'staffController@editStaff');
	Route::post('staff/{id}/update', 'staffController@updateStaff');
	Route::delete('staff/{id}/delete', 'staffController@deleteStaff');

// VEHICLES
	Route::post('addVehicle', 'vehicleController@addVehicle');
	Route::get('vehicle', 'vehicleController@getVehicle');
	Route::get('vehicle/{id}/edit', 'vehicleController@editVehicle');
	Route::post('vehicle/{id}/update', 'vehicleController@updateVehicle');
	Route::delete('vehicle/{id}/delete', 'vehicleController@deleteVehicle');

// ENTRY MODULE 
	Route::get('passEntryData', 'entryController@entryPassData');
	Route::post('addEntry', 'entryController@addEntry');
	Route::get('entry', 'entryController@getEntry');
	Route::get('entry/{id}/edit', 'entryController@editEntry');
	Route::post('entry/{id}/update', 'entryController@updateEntry');
	Route::delete('entry/{id}/delete', 'entryController@deleteEntry');

// EXPENSE MODULE
	Route::get('passExpenseData', 'expenseController@expensePassData');
	Route::post('AddExpense', 'expenseController@AddExpense');
	Route::get('expense', 'expenseController@getExpense');
	Route::get('expense/{id}/edit', 'expenseController@editExpense');
	Route::post('expense/{id}/update', 'expenseController@updateExpense');
	Route::delete('expense/{id}/delete', 'expenseController@deleteExpense');

// INCOME MODULES
	Route::get('clientIncome/{id}/show', 'balanceController@ClientIncomeBalance');












});
