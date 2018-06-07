<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('admin/categories', 'CategoryController@home')->name('admin.categories');

Route::get('admin/pages', 'PageController@home')->name('admin.pages');

Route::get('admin/tags', 'TagController@home')->name('admin.tags');

Route::get('admin/media', 'MediaController@home')->name('admin.media');
