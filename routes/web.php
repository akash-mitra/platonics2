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
Route::put('admin/categories/{category}', 'CategoryController@update')->name('categories.update');
Route::delete('admin/categories/{category}', 'CategoryController@destroy')->name('categories.destroy');

// Below URLs will return JSON as response and are to be considered for public api endpoints
Route::get('api/categories', 'CategoryController@index')->name('categories.index');
Route::get('api/categories/{category}', 'CategoryController@show')->name('categories.show');
Route::get('api/tags/categories/{category}', 'CategoryController@tags')->name('categories.tags');
Route::get('api/comments/categories/{category}', 'CategoryController@comments')->name('categories.comments');
Route::get('api/categories/{category?}/pages', 'CategoryController@pages')->name('categories.pages');

/*
|--------------------------------------------------------------------------
| Page Model
|--------------------------------------------------------------------------
*/

//Below URLs will return some view as response
Route::get('admin/pages', 'PageController@adminHome')->name('pages.home');
Route::get('admin/pages/create', 'PageController@create')->name('pages.create');
Route::get('admin/pages/{page}/edit', 'PageController@edit')->name('pages.edit');
Route::get('pages/{page}/{slug?}', 'FrontendController@page')->name('frontend.page');

// Below URLs will return JSON as response
Route::post('admin/pages', 'PageController@store')->name('pages.store');
Route::put('admin/pages/{page}', 'PageController@update')->name('pages.update');
Route::delete('admin/pages/{page}', 'PageController@destroy')->name('pages.destroy');
Route::put('admin/pages/{page}/publish', 'PageController@publish')->name('pages.publish');

// Below are public api endpoints returning JSON as response
Route::get('api/pages', 'PageController@index')->name('pages.index');
Route::get('api/pages/{page}', 'PageController@show')->name('pages.show');
Route::get('api/tags/pages/{page}', 'PageController@tags')->name('pages.tags');
Route::get('api/comments/pages/{page}', 'PageController@comments')->name('pages.comments');

/*
|--------------------------------------------------------------------------
| Tag Model
|--------------------------------------------------------------------------
*/

//Below URLs will return some view as response
Route::get('admin/tags', 'TagController@adminHome')->name('tags.home');
Route::get('admin/tags/create', 'TagController@create')->name('tags.create');
Route::get('admin/tags/{tag}/edit', 'TagController@edit')->name('tags.edit');
Route::get('tags/{tagName}', 'FrontendController@tag')->name('frontend.tag');

// Below URLs will return JSON as response
Route::post('admin/tags', 'TagController@store')->name('tags.store');
Route::put('admin/tags/{tag}', 'TagController@update')->name('tags.update');
Route::delete('admin/tags/{tag}', 'TagController@destroy')->name('tags.destroy');

// Below are public api endpoints returning JSON as response
Route::get('api/tags', 'TagController@index')->name('tags.index');
Route::get('api/tags/{tag}', 'TagController@show')->name('tags.show');
Route::get('api/tags/{tagName}/categories', 'TagController@categories')->name('tags.categories');
Route::get('api/tags/{tagName}/pages', 'TagController@pages')->name('tags.pages');
Route::get('api/tags/{tagName}/all', 'TagController@all')->name('tags.all');
Route::post('api/tags/attach', 'TagController@attach')->name('tags.attach');
Route::post('api/tags/detach', 'TagController@detach')->name('tags.detach');
Route::post('api/tags/fullattach', 'TagController@fullattach')->name('tags.fullattach');

/*
|--------------------------------------------------------------------------
| Comment Model
|--------------------------------------------------------------------------
*/

//Below URLs will return some view as response
Route::get('admin/comments', 'CommentController@adminHome')->name('comments.home');

// Below URLs will return JSON as response
Route::post('admin/comments', 'CommentController@store')->name('comments.store');
Route::put('admin/comments/{comment}', 'CommentController@update')->name('comments.update');
Route::delete('admin/comments/{comment}', 'CommentController@destroy')->name('comments.destroy');

/*
|--------------------------------------------------------------------------
| Media Model
|--------------------------------------------------------------------------
*/

//Below URLs will return some view as response
Route::get('admin/media', 'MediaController@adminHome')->name('media.home');
Route::get('admin/media/create', 'MediaController@create')->name('media.create');
Route::get('admin/media/{media}/edit', 'MediaController@edit')->name('media.edit');

// Below URLs will return JSON as response
Route::post('admin/media', 'MediaController@store')->name('media.store');
Route::put('admin/media/{media}', 'MediaController@update')->name('media.update');
Route::delete('admin/media/{media}', 'MediaController@destroy')->name('media.destroy');

// Below are public api endpoints returning JSON as response
Route::get('api/media', 'MediaController@index')->name('media.index');
Route::get('api/media/{media}', 'MediaController@show')->name('media.show');
Route::get('api/media/{media}/absolute', 'MediaController@absolute')->name('media.absolute');
Route::get('api/media/{media}/relative', 'MediaController@relative')->name('media.relative');
Route::get('api/media/{media}/optimize', 'MediaController@optimize')->name('media.optimize');

/*
|--------------------------------------------------------------------------
| Configuration Model
|--------------------------------------------------------------------------
*/

// Below URLs will return JSON as response
Route::post('admin/configs', 'ConfigurationController@store')->name('configurations.store');
Route::delete('admin/configs/{key}', 'ConfigurationController@destroy')->name('configurations.destroy');
Route::get('admin/configs', 'ConfigurationController@index')->name('configurations.index');
Route::get('admin/configs/{key}', 'ConfigurationController@show')->name('configurations.show');

/*
|--------------------------------------------------------------------------
| User Model
|--------------------------------------------------------------------------
*/

//Below URLs will return some view as response
Route::get('admin/users', 'UserController@adminHome')->name('users.home');
Route::get('admin/users/create', 'UserController@create')->name('users.create');
Route::get('admin/users/{user}/edit', 'UserController@edit')->name('users.edit');
Route::get('users/{slug}', 'FrontendController@user')->name('frontend.user');

// Below URLs will return JSON as response
Route::post('admin/users', 'UserController@store')->name('users.store');
Route::put('admin/users/{user}', 'UserController@update')->name('users.update');
Route::delete('admin/users/{user}', 'UserController@destroy')->name('users.destroy');
Route::put('admin/users/{user}/type', 'UserController@type')->name('users.type');

// Below are public api endpoints returning JSON as response
Route::get('api/users', 'UserController@index')->name('users.index');
Route::get('api/users/{user}', 'UserController@show')->name('users.show');
Route::get('api/users/{slug}/pages', 'UserController@pages')->name('users.pages');
//Route::get('api/users/{slug}/unpublished', 'UserController@unpublished')->name('users.unpublished');


//*** DUMMY TEST ***//
//Route::get('test', function () { return view('test'); });
//Route::get('analyticsga', 'AnalyticsController@test')->name('analytics.test');