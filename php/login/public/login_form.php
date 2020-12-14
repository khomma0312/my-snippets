<?php
session_start();
require_once('../common.php');
require_once('../classes/UserLogic.php');

$result = UserLogic::checkLogin();
if ($result) {
	header('Location: my_page.php');
	return;
}

$err = $_SESSION;

// セッション削除
$_SESSION = array();
session_destroy();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ログイン画面</title>
</head>
<body>
	<h2>ログインフォーム</h2>
	<?php echo get_err_msg($err['msg']); ?>
	<form action="login.php" method="POST">
		<p>
			<label for="email">メールアドレス：</label>
			<input type="text" name="email">
			<?php echo get_err_msg($err['email']); ?>
		</p>
		<p>
			<label for="password">パスワード：</label>
			<input type="password" name="password">
			<?php echo get_err_msg($err['password']); ?>
		</p>
		<p>
			<input type="submit" value="ログイン">
		</p>
	</form>
	<a href="signup_form.php">新規登録はこちら</a>
</body>
</html>