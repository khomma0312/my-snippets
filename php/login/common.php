<?php
/**
 * エラーメッセージがあればそのまま返し、なければ空文字を返す
 *
 * @param string $err_msg
 * @return string
 */
function get_err_msg($err_msg) {
	if ( isset($err_msg) ) {
		return '<p>' . $err_msg . '</p>';
	}
	return '';
}