<?php
// 1. ログインロジック作成
// 2. emailからユーザー取得ロジック作成
// 3. パスワード照会・セッションハイジャック対策
// 4. エラー分岐

// セッション開始（require_onceの前に入れておけば、UserLogic.phpでもsession_startした状態で処理がかけるため）
session_start();
require_once('../classes/UserLogic.php');

// エラーメッセージ
$err = [];

// バリデーション
if (! $email = filter_input(INPUT_POST, 'email')) {
	$err['email'] = 'メールアドレスを記入してください。';
}

if (! $password = filter_input(INPUT_POST, 'password') ) {
	$err['password'] = 'パスワードを記入してください。';
}

// エラーがあった場合、ログイン画面に戻してログインフォームにエラーメッセージを出す
if ( count($err) > 0 ) {
	// ログインする処理
	$_SESSION = $err;
	header('Location: login_form.php');
	return;
}

// ログイン判定
$result = UserLogic::login( $email, $password );
// ログイン失敗時の処理
if ( ! $result ) {
	header('Location: login_form.php');
	return;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ログイン完了</title>
</head>
<body>
	<h2>ログイン完了</h2>
	<p>ログインしました</p>
	<a href="./my_page.php">マイページへ</a>
</body>
</html>