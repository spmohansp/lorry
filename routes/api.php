<?php

use Illuminate\Http\Request;
Route::get('/', function(){
	return "Po Da Dai";
});
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



});