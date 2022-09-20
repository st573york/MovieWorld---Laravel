<?php

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

Route::get('/', 'MoviesController@index')->name('movies.index');

Auth::routes();

Route::group(['middleware' => ['auth']], function() {
    Route::get('/movies/{user}', 'MoviesController@show')->name('movies.user');
    Route::get('/profile/{user}', 'ProfileController@show')->name('profile.show');
    Route::post('/profile/{user}/update', 'ProfileController@update')->name('profile.update');
});

/**
 * AJAX Routes
 */
Route::get('/ajax/movie/sort', 'MoviesController@movie_sort');

Route::group(['prefix' => '/movies/{user}/ajax', 'middleware' => 'auth'], function () {
    /**
     * Movie
     */
    Route::get('movie/validate', 'MoviesController@movie_validate');
    Route::post('movie/store', 'MoviesController@movie_store');
    Route::post('movie/update', 'MoviesController@movie_update');
    Route::post('movie/delete', 'MoviesController@movie_delete');
    Route::post('movie/vote', 'MoviesController@movie_vote');

    /**
     * Review
     */
    Route::get('review/validate', 'MoviesController@review_validate');
    Route::post('review/store', 'MoviesController@review_store');

    /**
     * User
     */
    Route::post('user/delete', 'UsersController@user_delete');
});
