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
    return view('home');
});

Auth::routes();

// 全員
Route::group(['middleware' => ['auth', 'can:user-higher']], function () {
    // 楽曲一覧
    Route::get('/music', 'MusicController@index')->name('music.index');

    // プレイリストCRUD
    Route::resource('/playlist', 'PlaylistController');
});

  // 管理者以上
Route::group(['middleware' => ['auth', 'can:admin-higher']], function () {
    // 検索
    Route::get('/music/search', 'MusicController@searchform')->name('music.searchform');
    Route::post('/music/search', 'MusicController@search')->name('music.search');

    // ファイルアップロード
    Route::get('/music/upload', 'MusicController@upload')->name('music.upload');

    // 楽曲CRUD
    Route::resource('/music', 'MusicController');
});

  // システム管理者のみ
Route::group(['middleware' => ['auth', 'can:system-only']], function () {

});