<p align="center"><img src="https://larastagram-backet.s3-ap-northeast-1.amazonaws.com/logo.png"></p>


Larastagram
====== 

## 初めに
Laravel5.5を用いてinstagramのCloneアプリケーションを作成。<br>
作成した目的は自身のスキルアップの為に作成し、今後知識が増えるたびに新しい機能追加を行う予定。

## 概要
- Laravelにて開発。
- HTML/CSS/Bootstrapを使用して、実際のInstagramを意識したデザイン。
- 画像投稿・一覧表示・削除機能、いいね機能、コメント機能を実装。
- ログイン機能、ユーザー編集・詳細表示画面、パスワード変更機能を実装。
- バリデーションエラーの日本語化を実施。
- 独自のバリデーションルール実装
- PHPUnitを用いてのFeatureテストを実装

## 環境、フレームワーク
- PHP 7.2.24
- Laravel 5.5.48
- Bootstrap 4
- mysql  Ver 14.14
- Heroku Postgres
- heroku
- Amazon S3
- AWS Cloud9

## URL一覧

URL | 画面 |
----| ---- |
http://{domain}/admin/login | ログイン |
http://{domain}/admin/ | ダッシュボード | 
http://{domain}/admin/admins | 管理者一覧 |
http://{domain}/admin/admins/create | 管理者作成 |
http://{domain}/admin/admins/{admin_id} | 管理者詳細 |
http://{domain}/admin/admins/{admin_id}/edit | 管理者編集 |
http://{domain}/admin/users | ユーザ一覧 |
http://{domain}/admin/users/create | ユーザ作成 |
http://{domain}/admin/users/{user_id} | ユーザ詳細 |
http://{domain}/admin/users/{user_id}/edit | ユーザ編集 |
http://{domain}/admin/tasks | タスク一覧 |
http://{domain}/admin/tasks/create | タスク作成 |
http://{domain}/admin/tasks/{user_id} | タスク詳細 |
http://{domain}/admin/tasks/{user_id}/edit | タスク編集 |
http://{domain}/login | ログイン |
http://{domain}/ | ダッシュボード |
http://{domain}/tasks | タスク一覧 |
http://{domain}/tasks/create | タスク作成 |
http://{domain}/tasks/{task_id} | タスク詳細 |
http://{domain}/tasks/{task_id}/edit | タスク編集 |
http://{domain}/profiles/edit | プロフィール編集 |
