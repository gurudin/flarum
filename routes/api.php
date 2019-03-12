<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Api', 'middleware' => ['api']], function () {
    /**
     * Category
     */
    Route::get('/category', 'CategoryController@all')->name('api.category.all');

    /**
     * Posts
     */
    Route::get('/posts', 'PostsController@all')->name('api.posts.all');
    Route::get('/posts/{id?}', 'PostsController@detail')->name('api.posts.detail');
    // Route::get('/isRead', 'PostsController@isRead')->name('api.isRead');

    /**
     * Login & Register
     */
    Route::post('/register', 'UsersController@register')->name('api.register');
    Route::post('/login', 'UsersController@login')->name('api.login');
    Route::post('/logout', 'UsersController@logout')->name('api.logout');
});
