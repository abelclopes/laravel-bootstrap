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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/home', 'HomeController@index')->name('home');

Auth::routes(
    //['register' => false]
);
Route::namespace('Admin')->group(function () {
    //Route::get('user/{id}', function ($account, $id) {});
    Route::get('/Admin/user',  'UserController@index')->name('admin.user.index');
    Route::get('/Admin/user/add',  'UserController@add')->name('admin.user.add');
    Route::get('/Admin/user/edit',  'UserController@edit')->name('admin.user.edit');
    Route::post('/Admin/user/create',  'UserController@create')->name('admin.user.create');
    Route::get('/Admin/user/delete',  'UserController@delete')->name('admin.user.delete');
});
