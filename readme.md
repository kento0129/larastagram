Larastagram
====== 

## 初めに
Laravel5.5を用いてinstagramのCloneアプリケーションを作成。<br>
作成した目的は自身のスキルアップの為に作成し、今後知識が増えるたびに新しい機能追加を行う予定。

## 概要
- Laravelにて開発。
- HTML/CSS/Bootstrapを使用して、実際のInstagramを意識したデザイン。
- 画像投稿・一覧表示・削除機能、いいね機能、コメント機能、フォロー機能を実装。
- ログイン機能、ユーザー編集・詳細表示画面、パスワード変更機能を実装。
- ajaxを用いた非同期通信でのフォロー機能、いいね機能を実装。
- バリデーションエラーの日本語化を実施。
- 独自のバリデーションルール実装
- PHPUnitを用いてのFeatureテストを実装
- herokuでは画像をAWSでアップロードし、ローカルではパブリックディスクにアップロード

## 環境、フレームワーク
- PHP 7.2
- Laravel 5.5
- Bootstrap 4
- mysql  Ver 14.14
- Heroku Postgres
- heroku
- Amazon S3
- AWS Cloud9

## インストール
- git clone https://github.com/kento0129/larastagram.git projectname
- cd projectname
- composer update
- create database [データベース名];
- .env.exampleを.envにリネーム
- .envを環境に合わせて修正
- php artisan key:generate
- php artisan migrate
- php artisan serve

## Composer で追加したパッケージ
- composer require league/flysystem-aws-s3-v3

## URL一覧

URL | 画面 |
----| ---- |
http://{domain}/ | 投稿一覧画面 |
http://{domain}/login | ログイン | 
http://{domain}/register | ユーザー新規登録 |
http://{domain}/users/edit | ユーザー編集画面 |
http://{domain}/users/update | ユーザー更新画面 |
http://{domain}/users/password | パスワード編集画面 |
http://{domain}/users/password/change | パスワード変更画面 |
http://{domain}/{user_id} | ユーザー詳細画面 |
http://{domain}/posts/post_photo/{post_id} | 投稿写真画面 |
http://{domain}/posts/new | 投稿新規画面 |
http://{domain}/posts | 投稿新規処理 |
http://{domain}/posts/delete/{post_id} | 投稿削除処理 |
http://{domain}/likes/posts/{post_id} | いいね処理 |
http://{domain}/likes/delete/{like_id} | いいね取消処理 |
http://{domain}/comments/posts/{post_id} | コメント投稿処理 |
http://{domain}/comments/delete/{comment_id} | コメント取消処理 |
http://{domain}/followers/posts/{followed_id} | フォロー登録処理 |
http://{domain}/followers/delete/{followed_id} | フォロー取消処理 |
http://{domain}/followers/ajax/posts/{followed_id} | フォロー登録処理ajax |
http://{domain}/followers/ajax/delete/{followed_id} | フォロー取消処理ajax |
