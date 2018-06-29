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
Route::get('/', 'FrontendController@home');

// AUTH
Auth::routes();

// SOCIALITE
Route::get('login/{provider}', 'Auth\RegisterController@redirectToProvider');
Route::get('login/{provider}/callback', 'Auth\RegisterController@handleProviderCallback');

/*
|--------------------------------------------------------------------------
| Category Model
|--------------------------------------------------------------------------
*/

//Below URLs will return some view as response
Route::get('admin/categories', 'CategoryController@adminHome')->name('categories.home');
Route::get('admin/categories/create', 'CategoryController@create')->name('categories.create');
Route::get('admin/categories/{category}/edit', 'CategoryController@edit')->name('categories.edit');
Route::get('categories/{category}', 'FrontendController@category')->name('frontend.category');

// Below URLs will return JSON as response
Route::post('admin/categories', 'CategoryController@store')->name('categories.store');
Route::patch('admin/categories/{category}', 'CategoryController@update')->name('categories.update');
Route::delete('admin/categories/{category}', 'CategoryController@destroy')->name('categories.destroy');

// Below URLs will return JSON as response and are to be considered for public api endpoints
Route::get('api/categories', 'CategoryController@index')->name('categories.index');
Route::get('api/categories/{category}', 'CategoryController@get')->name('categories.get');

/*
|--------------------------------------------------------------------------
| Page Model
|--------------------------------------------------------------------------
*/
//Below URLs will return some view as response
Route::get('admin/pages', 'PageController@adminHome')->name('pages.home');
Route::get('admin/pages/create', 'PageController@create')->name('pages.create');
Route::get('admin/pages/{Page}/edit', 'PageController@edit')->name('pages.edit');
Route::get('pages/{page}/{slug?}', 'FrontendController@page')->name('frontend.page');

// Below URLs will return JSON as response
Route::post('admin/pages', 'PageController@store')->name('pages.store');
Route::patch('admin/pages/{Page}', 'PageController@update')->name('pages.update');
Route::delete('admin/pages/{Page}', 'PageController@destroy')->name('pages.destroy');
Route::put('admin/pages/{page}/publish', 'PageController@publish')->name('pages.publish');

// Below are public api endpoints returning JSON as response
Route::get('api/pages', 'PageController@index')->name('pages.index');
Route::get('api/pages/{page}', 'PageController@get')->name('pages.get');

// Route::get('admin/pages', 'PageController@home')->name('pages.home');
// Route::get('admin/pages/create', 'PageController@create')->name('pages.create');
// Route::resource('pages', 'PageController')->except('create');
// Route::put('pages/{page}/publish', 'PageController@publish')->name('pages.publish');
// Route::get('pages/comments/{page}', 'PageController@comments')->name('pages.comments');
// Route::get('pages/{page}/{slug?}', 'PageController@show')->name('frontend.page');

// TAG
Route::get('admin/tags', 'TagController@home')->name('tags.home');
Route::resource('tags', 'TagController');
Route::post('tags/attach', 'TagController@attach')->name('tags.attach');
Route::post('tags/detach', 'TagController@detach')->name('tags.detach');
Route::get('tags/{tag_name}/categories', 'TagController@categories')->name('tags.categories');
Route::get('tags/{tag_name}/pages', 'TagController@pages')->name('tags.pages');
Route::get('tags/{tag_name}/all', 'TagController@all')->name('tags.all');

// COMMENT
Route::resource('comments', 'CommentController');
Route::get('api/comments/pages/{page}', 'PageController@comments')->name('pages.comments');


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
