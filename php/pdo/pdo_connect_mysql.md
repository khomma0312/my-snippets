# DockerでPHPコンテナからMySQLコンテナのDBにPDOで接続する際の設定
## PHPコンテナにPDOを使うためのパッケージをインストールする
Dockerfile
```
FROM php:7.3-apache
RUN apt-get update \
	&& apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev \
	&& docker-php-ext-install pdo_mysql mysqli mbstring gd iconv
```

## DBとテーブル、root以外に新規ユーザーを作成しておく（文字コードなどは適宜設定）
```
// DB作成
CREATE DATABASE test;
// テーブル作成
USE test;
CREATE TABLE users (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
	email VARCHAR(255) UNIQUE,
    created_at TIMESTAMP DEFAULT NOW()
);
// ユーザー作成（@のあとはlocalhostだとうまくいかないので、'%'を指定する）
grant all on users.* to dbuser@'%' identified by 'password';
```

## PDOでDBに接続する際にホスト名をDBコンテナのサービス名に設定する
env.php
```
define('DB_HOST', 'mysql');
define('DB_NAME', 'test');
define('DB_USER', 'dbuser');
define('DB_PASS', 'dbuser');
```

dbconnect.php
```
require_once('./env.php');
function connect() {
	$host = DB_HOST;
	$db   = DB_NAME;
	$user = DB_USER;
	$pass = DB_PASS;

	$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

	try {
		$pdo = new PDO($dsn, $user, $pass, [
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
		]);
		echo '成功です！';
	} catch(PDOException $e) {
		echo '接続失敗です：' . $e->getMessage();
		exit;
	}
}

connect();
```
