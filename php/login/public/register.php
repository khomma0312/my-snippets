<?php
require_once('../classes/UserLogic.php');
session_start();

// エラーメッセージ
$err = [];

$token = filter_input(INPUT_POST, 'csrf_token');
// トークンがない、もしくはトークンが一致しない場合、処理を中止
if ( ! isset($token) || $token !== $_SESSION['csrf_token'] ) {
	exit('不正なリクエストです。');
}

// 1回目に送信された時にセッションが消される→2回目に送信された際に、セッションが消えているので不正なリクエストになる（二重送信の防止になる）
unset($_SESSION['csrf_token']);

// バリデーション
if (! $user = filter_input(INPUT_POST, 'username')) {
	$err[] = 'ユーザー名を記入してください';
}

if (! $email = filter_input(INPUT_POST, 'email')) {
	$err[] = 'メールアドレスを記入してください';
}

$password = filter_input(INPUT_POST, 'password');
// 正規表現
if (! preg_match('/\A[a-z\d]{8,100}+\z/i', $password) ) {
	$err[] = 'パスワードは英数字8文字以上100文字以下にしてください。';
}
$password_conf = filter_input(INPUT_POST, 'password_conf');
if ( $password !== $password_conf ) {
	$err[] = '確認用パスワードと異なっています。';
}

if ( count($err) === 0 ) {
	// ユーザーを登録する処理
	$has_created = UserLogic::createUser( $_POST );
	if ( ! $has_created ) {
		$err[] = '登録に失敗しました';
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ユーザー登録完了画面</title>
</head>
<body>
	<?php if (count($err) > 0): ?>
		<?php foreach($err as $e): ?>
			<p><?php echo $e ?></p>
		<?php endforeach; ?>
	<?php else: ?>
		<p>ユーザー登録が完了しました。</p>
	<?php endif; ?>
	<a href="signup_form.php">戻る</a>
</body>
</html>