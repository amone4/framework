<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Request {
	const TYPE_WEB = 0;
	const TYPE_API = 1;
	private static $requestType;

	public static function processRequest() {
		$return = [];

		// getting request
		self::$requestType = self::TYPE_WEB;
		if (isset($_GET['url'])) {
			// sanitizing request
			$url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);

			// checking if its an API request
			if (substr($url, 0, 3) === 'api') {
				if (!isset($url[3])) {
					self::$requestType = self::TYPE_API;
					$url = '';
				} elseif ($url[3] === '/') {
					self::$requestType = self::TYPE_API;
					$url = substr($url, 4);
				}
			}

		// empty request
		} else $url = '';

		// converting URL into array
		$url = explode('/', $url);

		// determining the component
		if (isset($url[0]) && !empty($url[0])) {
			array_push($return, $url[0]);
			unset($url[0]);
		} else array_push($return, 'pages');

		// check for second part of url
		if (isset($url[1])) {
			array_push($return, $url[1]);
			unset($url[1]);
		} else array_push($return, 'index');

		// getting all params
		if ($url) array_push($return, array_values($url));
		else array_push($return, []);

		return $return;
	}

	public static function isWebRequest() {
		return self::$requestType === self::TYPE_WEB;
	}

	public static function isApiRequest() {
		return self::$requestType === self::TYPE_API;
	}

	public static function denyWebRequest() {
		self::$requestType = self::TYPE_API;
	}

	public static function denyApiRequest() {
		self::$requestType = self::TYPE_WEB;
	}
}