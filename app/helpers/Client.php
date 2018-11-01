<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Client {
	const TYPE_MISC = 0;
	const TYPE_USER = 1;

	private static $type = null;
	private static $id = null;

	/**
	 * Function determines the type of user
	 * Sets $type to one of the constants
	 * If $type === TYPE_MISC, then $id = null
	 * Otherwise, $id = id of that user in database
	 */
	private static function get() {
		if (isset($_SESSION['client_type']) && !empty($_SESSION['client_type'])) {
			$type = (integer) $_SESSION['client_type'];
			$model = '';
			if ($type === self::TYPE_USER) {
				$model = 'User';
			}
			if (!empty($model)) {
				if (isset($_SESSION['client_id']) && !empty($_SESSION['client_id'])) {
					$id = Crypt::decrypt($_SESSION['client_id']);
					if (ctype_digit($id)) {
						$model = App::getModel($model);
						$model->select($id);
						if ($model->rowCount() === 1) {
							self::$type = $type;
							self::$id = (integer) $id;
							return;
						}
					}
				}
			}
		}
		self::$type = self::TYPE_MISC;
	}

	/**
	 * Function stores the login session
	 * @param $id = id of that user as in the database
	 * @param int $type
	 */
	public static function set($id, $type = self::TYPE_USER) {
		if (self::TYPE_USER === $type) {
			if (ctype_digit($id)) {
				$_SESSION['client_type'] = self::$type = $type;
				$_SESSION['client_id'] = Crypt::encrypt($id);
				self::$id = $id;
			}
		}
	}

	/**
	 * Function destroys any client session
	 * Usually used for logout
	 */
	public static function destroy() {
		if (isset($_SESSION['client_type'])) unset($_SESSION['client_type']);
		if (isset($_SESSION['client_id'])) unset($_SESSION['client_id']);
		self::$type = Client::TYPE_MISC;
		self::$id = null;
	}

	public static function getType() {
		if (self::$type === null)
			self::get();
		return self::$type;
	}

	public static function getId() {
		if (self::$type === null)
			self::get();
		return self::$id;
	}
}