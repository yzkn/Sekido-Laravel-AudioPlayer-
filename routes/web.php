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

Route::get('/home', 'HomeController@index')->name('home');

// 全員
Route::group(['middleware' => ['auth', 'can:user-higher']], function () {
    // 楽曲一覧
    Route::get('/music', 'MusicController@index')->name('music.index');
});

  // 管理者以上
Route::group(['middleware' => ['auth', 'can:admin-higher']], function () {
    // ファイルアップロード
    Route::get('/music/upload', 'MusicController@upload')->name('music.upload');
    // 楽曲CRUD
    Route::resource('/music', 'MusicController');
});

  // システム管理者のみ
Route::group(['middleware' => ['auth', 'can:system-only']], function () {

});