<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/m', function () {
    return view('app');
});

Route::group(['namespace' => 'Frontend'], function () {
    Route::get('/', 'SiteController@index')->name('admin.home');
});

Route::group(['namespace' => 'Web', 'prefix' => 'admin.cms'], function () {
    Auth::routes();
    Route::get('/', 'SiteController@index')->name('admin.home');
    Route::post('/upload', 'SiteController@upload')->name('admin.upload');

    // Category
    Route::match(['get', 'post'], '/category/list', 'CategoryController@list')->name('admin.category.list');
    Route::match(['get', 'post'], '/category/save', 'CategoryController@save')->name('admin.category.save');

    // Post
    Route::match(['get', 'post'], '/posts/list', 'PostsController@list')->name('admin.posts.list');
    Route::match(['get', 'post'], '/posts/save', 'PostsController@save')->name('admin.posts.save');

    // User
    Route::match(['get', 'post'], '/users/list', 'UsersController@list')->name('admin.users.list');
    Route::match(['get', 'post'], '/users/code', 'UsersController@code')->name('admin.users.code');
});
