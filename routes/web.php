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

//投稿一覧画面
Route::get('/', 'PostsController@index'); 

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['prefix' => 'users'], function() {
  //ユーザ編集画面
  Route::get('/edit', 'UsersController@edit')->name('users.edit');
  //ユーザ更新画面
  Route::post('/update', 'UsersController@update')->name('users.update');
  //パスワード編集画面
  Route::get('/password', 'UsersController@password')->name('users.password');
  //パスワード変更画面
  Route::post('/password/change', 'UsersController@change')->name('users.password.change');
  // ユーザ詳細画面
  Route::get('/{user_id}', 'UsersController@show')->name('users');
    
});

Route::group(['prefix' => 'posts'], function() {
  //投稿新規画面
  Route::get('/new', 'PostsController@new')->name('posts.new');
  //投稿新規処理
  Route::post('/','PostsController@store')->name('posts');
  //投稿削除処理
  Route::get('/delete/{post_id}', 'PostsController@destroy')->name('posts.delete');
});

Route::group(['prefix' => 'likes'], function() {
  //いいね処理
  Route::get('/posts/{post_id}', 'LikesController@store')->name('likes.posts');
  //いいね取消処理
  Route::get('/delete/{like_id}', 'LikesController@destroy')->name('likes.delete');
});

Route::group(['prefix' => 'comments'], function() {
  //コメント投稿処理
  Route::post('/posts/{post_id}','CommentsController@store')->name('comments.posts');
  //コメント取消処理
  Route::get('/delete/{comment_id}', 'CommentsController@destroy')->name('comments.delete');
});
