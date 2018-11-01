<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Response {
	private static $response = [];

	public static function fatal($message = 'Invalid URL') {
		self::$response = ['fatal' => $message];
		App::stop();
	}

	public static function redirect($location = '') {
		self::$response['redirect'] = $location;
		App::stop();
	}

	public static function success($message) {
		self::pushMessage($message, 'success');
	}

	public static function info($message) {
		self::pushMessage($message, 'info');
	}

	public static function error($message) {
		self::pushMessage($message, 'error');
	}

	private static function pushMessage($message, $type) {
		if (!isset(self::$response['messages']))
			self::$response['messages'] = [];
		array_push(self::$response['messages'], [
			'type' => $type,
			'message' => $message
		]);
	}

	public static function view($view, $data = []) {
		self::$response['view'] = $view;
		if ($data !== []) self::$response['data'] = $data;
	}

	public static function data($data) {
		self::$response['data'] = $data;
	}

	public static function session($data) {
		self::$response['session'] = $data;
	}

	public static function send() {
		if (!isset(self::$response['fatal']) && !isset(self::$response['redirect'])) {
			if (!isset(self::$response['messages']))
				self::$response['messages'] = [];
			if (!isset(self::$response['data']))
				self::$response['data'] = [];
			if (!isset(self::$response['session']))
				self::$response['session'] = [];
		}
		Renderer::render(Response::$response);
	}
}