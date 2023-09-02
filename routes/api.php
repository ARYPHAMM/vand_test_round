<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1', 'namspace' => 'Api'], function () {
    Route::post('login', 'Api\AuthController@login')->name('auth-login');
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', 'Api\AuthController@register');
        Route::get('/me', 'Api\AuthController@me')->middleware('auth:api');
        Route::put('/update', 'Api\AuthController@update')->name('auth-update')->middleware('auth:api');
        Route::put('/update/password', 'Api\AuthController@updatePassword')->name('auth-update-password')->middleware('auth:api');
        Route::get('/logout', 'Api\AuthController@logout')->name('auth-logout')->middleware('auth:api');
    });
    Route::get('manage/stories', 'Api\StoreController@indexOfMe')->middleware('auth:api');
    Route::group(['prefix' => 'stories'], function () {
        Route::get('/', 'Api\StoreController@index');
        Route::post('/', 'Api\StoreController@create');
        Route::put('/{id}', 'Api\StoreController@update');
        Route::get('/{id}', 'Api\StoreController@detail');
        Route::delete('/{id}', 'Api\StoreController@delete');
    });
    Route::get('manage/products', 'Api\ProductController@indexOfMe')->middleware('auth:api');
    Route::group(['prefix' => 'products'], function () {
        Route::get('/', 'Api\ProductController@index');
        Route::post('/', 'Api\ProductController@create');
        Route::put('/{id}', 'Api\ProductController@update');
        Route::get('/{id}', 'Api\ProductController@detail');
        Route::delete('/{id}', 'Api\ProductController@delete');
    });
    Route::post('uploads/images', 'Api\UploadFileController@storeImage')->name('upload.image');
});
