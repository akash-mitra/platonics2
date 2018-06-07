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

Route::get('admin/categories', 'CategoryController@home')->name('categories.home');

Route::get('admin/pages', 'PageController@home')->name('pages.home');
Route::get('admin/pages/create', 'PageController@create')->name('pages.create');

Route::get('admin/tags', 'TagController@home')->name('tags.home');

Route::get('admin/media', 'MediaController@home')->name('media.home');
