<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Session {
	private static $keys = [];

	public static function init() {
		if (Request::isApiRequest() && isset($_POST['session'])) {
			foreach ($_POST['session'] as $key => $value) {
				$key = trim(filter_var($key, FILTER_SANITIZE_STRING));
				array_push(Session::$keys, $key);
				$_SESSION[$key] = trim(filter_var($value, FILTER_SANITIZE_STRING));
			}
		}
	}

	public static function process() {
		if (Request::isApiRequest() && self::$keys !== []) {
			$session = [];
			foreach (self::$keys as $key) {
				$session[$key] = $_SESSION[$key];
				unset($_SESSION[$key]);
			}
			Response::session($_SESSION);
		}
	}
}