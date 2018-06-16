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

Route::get('login/{provider}', 'Auth\RegisterController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\RegisterController@handleProviderCallback');

Route::get('admin/categories', 'CategoryController@home')->name('categories.home');

Route::get('admin/pages', 'PageController@home')->name('pages.home');
Route::get('admin/pages/create', 'PageController@create')->name('pages.create');

Route::get('admin/tags', 'TagController@home')->name('tags.home');

Route::get('admin/media', 'MediaController@home')->name('media.home');


Route::resource('categories', 'CategoryController');

Route::resource('pages', 'PageController')->except('create');
Route::put('pages/{page}/publish', 'PageController@publish')->name('pages.publish');
Route::get('pages/{page}/comments', 'PageController@comments')->name('pages.comments');

Route::resource('tags', 'TagController');
Route::post('tags/attach', 'TagController@attach')->name('tags.attach');
Route::post('tags/detach', 'TagController@detach')->name('tags.detach');
Route::get('tags/{tag}/categories', 'TagController@taggedCategories')->name('tags.categories');
Route::get('tags/{tag}/pages', 'TagController@taggedPages')->name('tags.pages');

Route::resource('comments', 'CommentController');

Route::resource('media', 'MediaController');

Route::post('configs', 'ConfigurationController@store')->name('configurations.store');
Route::get('configs/{key}', 'ConfigurationController@show')->name('configurations.show');
Route::get('configs', 'ConfigurationController@index')->name('configurations.index');
Route::delete('configs/{key}', 'ConfigurationController@destroy')->name('configurations.destroy');