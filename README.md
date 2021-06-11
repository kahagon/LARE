# LARE(られ) - Laravel Application Running Environment

docker-compose を用いLaravelアプリの実行環境を構築する

#### 対応環境
CentOS7

## CentOS7 へのインストール

#### 手順

1. Laravel プロジェクト内にこのディレクトリを保存
1. Laravel プロジェクトの以下の環境変数(.env)が定義済みであることを確認
    * `APP_ENV`  
        このアプリの実行環境。 letsencrypt の設定に用いられる  
        * local
        * staging
        * production
    * `APP_DOMAIN`  
        このアプリをホストするサーバーのドメイン名。 letsencrypt の設定に用いられる
    * `DB_DATABASE`  
        アプリの使用する DB 名称。この名前の DB が作成される
    * `DB_USERNAME`  
        `root` は指定不可
    * `DB_PASSWORD`  
        上記 `DB_USERNAME` に対するパスワード
    * 以下の環境変数は必須ではないものの定義されている方が望ましい
        * `CONTAINER_SSL`  
            SSLサーバー用コンテナの名称
        * `CONTAINER_WEB`  
            WEBサーバー用コンテナの名称
        * `CONTAINER_DB`  
            DBサーバー用コンテナの名称
1. install-centos7 を実行

アプリはインストール後に稼働状態になる。


## 起動

```
./app-start
```

## 停止

```
./app-stop
```

## 更新

```
./app-deploy
```
アプリのソースを更新した場合に使用。以下の処理が実行される。

  * JavaScript や CSS のコンパイル
  * php ライブラリの依存関係の更新
