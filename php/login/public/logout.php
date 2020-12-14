<?php
require_once('../classes/UserLogic.php');
session_start();

// POSTで受け取っているか判定
if (! $logout = filter_input(INPUT_POST, 'logout')) {
	exit('不正なリクエストです');
}

// ログインしているかどうか判定し、していなかったら新規登録画面に戻す。セッションが切れていたら、ログインしてくださいとメッセージを出す
$result = UserLogic::checkLogin();

if (!$result) {
	exit('セッションが切れましたので、ログインし直してください');
}

// ログアウトする
UserLogic::logout();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ログアウト</title>
</head>
<body>
	<h2>ログアウト完了</h2>
	<p>ログアウトしました！</p>
	<a href="login_form.php">ログイン画面へ</a>
</body>
</html>