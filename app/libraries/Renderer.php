<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Renderer {
	public static function render($response) {
		if (Request::isApiRequest())
			Renderer::renderJSON($response);
		else
			Renderer::renderHTML($response);
	}

	private static function renderJSON($response) {
		header('Content-type: application/json');
		if (!empty($response['view'])) {
			$view = explode('/', $response['view']);
			if (isset($view[1]))
				$response['view'] = $view[0] . '/' . $view[1];
			else
				$response['view'] = App::$controller . '/' . $view[0];
		}
		echo json_encode($response);
	}

	private static function renderHTML($response) {
		if (isset($response['fatal']))
			self::generateErrorPage($response['fatal']);

		foreach ($response['messages'] as $message)
			Messages::{$message['type']}($message['message']);

		if (isset($response['redirect']))
			header('Location: ' . URL_ROOT . '/' . $response['redirect']);

		$response['view'] = explode('/', $response['view']);
		$viewPath = APP_ROOT . '/views/';
		if (isset($response['view'][1])) {
			$viewPath .= $response['view'][0] . '/';
			unset($response['view'][0]);
		} else $viewPath .= App::$controller . '/';
		$data = $response['data'];

		if (file_exists($viewPath . $response['view'][0] .  '.php')) {
			if (file_exists($viewPath . 'header.php'))
				require_once $viewPath . 'header.php';
			else require_once APP_ROOT . '/views/inc/header.php';
			echo '<div id="container">';
			require_once $viewPath . $response['view'][0] . '.php';
			echo '</div>';
			if (file_exists($viewPath . 'footer.php'))
				require_once $viewPath . 'footer.php';
			else require_once APP_ROOT . '/views/inc/footer.php';
		} else self::generateErrorPage('View doesn\'t exist');
	}

	private static function generateErrorPage($message) {
		require_once APP_ROOT . '/views/inc/message.php';
		die();
	}
}