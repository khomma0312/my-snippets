<?php
require_once('../dbconnect.php');

class UserLogic {
	/**
	 * ユーザーを登録する
	 * @param array $user_data
	 * @return bool $result
	 */
	public static function createUser( $user_data ) {
		$result = false;

		$sql = 'INSERT INTO users (name, email, password) VALUES(?, ?, ?)';

		// ユーザーデータを配列に入れる
		$arr = [];
		$arr[] = $user_data['username'];
		$arr[] = $user_data['email'];
		$arr[] = password_hash($user_data['password'], PASSWORD_DEFAULT);

		try {
			$stmt = connect()->prepare($sql);
			$result = $stmt->execute($arr);
			return $result;
		} catch (\Exception $e) {
			return $result;
		}
	}

	/**
	 * ログイン処理
	 * @param string $email
	 * @param string $password
	 * @return bool $result
	 */
	public static function login($email, $password) {
		// 結果をfalseに定義
		$result = false;

		// ユーザーをemailから検索して取得
		$user = self::get_user_by_email($email);

		// emailが合致しなかった場合、エラーメッセージをセッションに入れてfalseでreturn
		if (! $user ) {
			$_SESSION['msg'] = 'emailが一致しません。';
			return $result;
		}

		// パスワードの照会
		if (password_verify($password, $user['password'])) {
			// ログイン成功
			session_regenerate_id(true);
			$_SESSION['login_user'] = $user;
			$result = true;
			return $result;
		}

		// パスワードが合致しなかった場合
		$_SESSION['msg'] = 'パスワードが一致しません。';
		return $result;
	}

	/**
	 * emailからユーザーを取得
	 *
	 * @param string $email
	 * @return array|bool $user|false
	 */
	public static function get_user_by_email($email) {
		// SQLの準備
		$sql = 'SELECT * FROM users WHERE email = ?';

		// emailを配列に入れる
		$arr = [];
		$arr[] = $email;

		try {
			// SQLの実行
			$stmt = connect()->prepare($sql);
			$stmt->execute($arr);
			// SQLの結果を返す
			$user = $stmt->fetch();
			return $user;
		} catch (\Exception $e) {
			return false;
		}
	}

	/**
	 * ログインチェック
	 *
	 * @param void
	 * @return bool $result
	 */
	public static function checkLogin() {
		$result = false;
		// セッションにログインユーザーが入っていなかったらfalse
		if ( isset($_SESSION['login_user']) && $_SESSION['login_user']['id'] > 0 ) {
			return $result = true;
		}

		return $result;
	}

	/**
	 * ログアウト処理
	 * @return void
	 */
	public static function logout() {
		$_SESSION = array();
		session_destroy();
	}
}