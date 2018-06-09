<?php


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::resource('categories', 'CategoryController');

Route::resource('pages', 'PageController')->except('create');
Route::put('pages/{page}/publish', 'PageController@publish')->name('pages.publish');
Route::get('pages/{page}/comments', 'PageController@comments')->name('pages.comments');

Route::resource('tags', 'TagController');
Route::post('tags/attach', 'TagController@attach')->name('tags.attach');
Route::post('tags/detach', 'TagController@detach')->name('tags.detach');

Route::resource('comments', 'CommentController');

Route::resource('media', 'MediaController');

// Route::get('/test', function() {
//     return response()->json(Auth::guest());
// });
