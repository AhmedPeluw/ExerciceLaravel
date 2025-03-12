<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/reset-password/{token}', function () {
    return view('welcome');
})->where('token', '.*')->name('password.reset');

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');
