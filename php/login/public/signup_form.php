<?php
require_once('../functions.php');
require_once('../common.php');
require_once('../classes/UserLogic.php');

session_start();

$result = UserLogic::checkLogin();
if ($result) {
	header('Location: my_page.php');
	return;
}

$login_err = isset($_SESSION['login_err']) ? $_SESSION['login_err'] : null;
unset($_SESSION['login_err']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ユーザー登録</title>
</head>
<body>
	<h2>ユーザー登録フォーム</h2>
	<?php echo get_err_msg($login_err); ?>
	<form action="register.php" method="POST">
		<p>
			<label for="username">ユーザー名：</label>
			<input type="text" name="username">
		</p>
		<p>
			<label for="email">メールアドレス：</label>
			<input type="text" name="email">
		</p>
		<p>
			<label for="password">パスワード：</label>
			<input type="password" name="password">
		</p>
		<p>
			<label for="password_conf">パスワード確認：</label>
			<input type="password" name="password_conf">
		</p>
		<input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
		<p>
			<input type="submit" value="新規登録">
		</p>
	</form>
	<a href="login_form.php">ログインする</a>
</body>
</html>