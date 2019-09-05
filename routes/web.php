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
    // 楽曲登録
    Route::get('/music/regist', 'MusicController@regist')->name('music.regist');
    Route::post('/music/regist', 'MusicController@createData')->name('music.regist');

    // 楽曲編集
    Route::get('/music/edit/{music_id}', 'MusicController@edit')->name('music.edit');
    Route::post('/music/edit/{music_id}', 'MusicController@updateData')->name('music.edit');

    // 楽曲削除
    Route::post('/music/delete/{music_id}', 'MusicController@deleteData');
});

  // システム管理者のみ
Route::group(['middleware' => ['auth', 'can:system-only']], function () {

});