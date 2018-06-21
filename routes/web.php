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

// LANDING
Route::get('/', function () {
    return view('welcome');
});

// AUTH
Auth::routes();

// SOCIALITE
Route::get('login/{provider}', 'Auth\RegisterController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\RegisterController@handleProviderCallback');

// CATEGORY
Route::get('admin/categories', 'CategoryController@home')->name('categories.home');
Route::get('admin/categories/create', 'CategoryController@create')->name('categories.create');
Route::resource('categories', 'CategoryController');

// PAGE
Route::get('admin/pages', 'PageController@home')->name('pages.home');
Route::get('admin/pages/create', 'PageController@create')->name('pages.create');
Route::resource('pages', 'PageController')->except('create');
Route::put('pages/{page}/publish', 'PageController@publish')->name('pages.publish');
Route::get('pages/comments/{page}', 'PageController@comments')->name('pages.comments');
Route::get('pages/{page}/{slug?}', 'PageController@show')->name('pages.slug');

// TAG
Route::get('admin/tags', 'TagController@home')->name('tags.home');
Route::resource('tags', 'TagController');
Route::post('tags/attach', 'TagController@attach')->name('tags.attach');
Route::post('tags/detach', 'TagController@detach')->name('tags.detach');
Route::get('tags/{tag}/categories', 'TagController@categories')->name('tags.categories');
Route::get('tags/{tag}/pages', 'TagController@pages')->name('tags.pages');

// COMMENT
Route::resource('comments', 'CommentController');

// MEDIA
Route::get('admin/media', 'MediaController@home')->name('media.home');
Route::resource('media', 'MediaController');
Route::get('media/{media}/absolute', 'MediaController@absolute')->name('media.absolute');
Route::get('media/{media}/relative', 'MediaController@relative')->name('media.relative');
Route::get('media/{media}/optimize', 'MediaController@optimize')->name('media.optimize');

// CONFIGURATION
Route::post('configs', 'ConfigurationController@store')->name('configurations.store');
Route::get('configs/{key}', 'ConfigurationController@show')->name('configurations.show');
Route::get('configs', 'ConfigurationController@index')->name('configurations.index');
Route::delete('configs/{key}', 'ConfigurationController@destroy')->name('configurations.destroy');

// DUMMY TEST
Route::get('analyticsga', 'AnalyticsController@test')->name('analytics.test');
Route::get('test', function () { return view('test'); });
