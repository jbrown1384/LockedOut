<?php

// Auth::routes();

Route::get('/', 'HomeController@index')->name('home');
Route::post('/', 'HomeController@store')->name('store');
