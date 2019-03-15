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
    Route::get('/', 'SiteController@index')->name('frontend.home');
    Route::post('/login', 'UsersController@login')->name('frontend.login');
    Route::post('/register', 'UsersController@register')->name('frontend.register');
    Route::get('/logout', 'UsersController@logout')->name('frontend.logout');
    Route::get('/post/{id}', 'PostsController@post')->name('frontend.post');
});

Route::group(['namespace' => 'Web', 'prefix' => 'admin.cms'], function () {
    Auth::routes();
    Route::get('/', 'SiteController@index')->name('admin.home');
    
    // Upload
    Route::post('/upload', 'SiteController@upload')->name('admin.upload');
    Route::post('/generate', 'SiteController@generate')->name('admin.generate');

    // Site
    Route::match(['get', 'post'], '/recomment/list', 'RecommentController@list')->name('admin.recomment.list');
    Route::match(['get', 'post'], '/recomment/save', 'RecommentController@save')->name('admin.recomment.save');

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
