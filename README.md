# Laravel-Mp3Player-Sample

---

## clone

```ps
$ git clone https://github.com/YA-androidapp/Laravel-Mp3Player-Sample
```

## ライブラリのインストール

```ps
$ composer install
```

## 設定ファイル

```ps
$ cp .env.example .env
$ php artisan key:generate
```

## DB の準備

```ps
$ touch database/database.sqlite
$ php artisan migrate
```

## シンボリックリンク生成

```cmd
$ php artisan storage:link
```

## 動作確認

```ps
$ php artisan serve
```

[http://127.0.0.1:8000](http://127.0.0.1:8000) にアクセス

## ユーザー追加

ブラウザから実施

### システム管理者権限付与

システム管理者にしたいユーザーの `role` に `1` を設定
