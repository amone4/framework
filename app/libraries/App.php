<?php

defined('_INDEX_EXEC') or die('Restricted access');

class App {
	private static $controllers = [];
	private static $models = [];
	public static $controller;

	private static function getAllControllers() {
		foreach (new DirectoryIterator(APP_ROOT . '/controllers') as $fileInfo)
			if (!$fileInfo->isDot())
				self::$controllers[ucwords($fileInfo->getFilename())] = null;
	}

	private static function getAllModels() {
		foreach (new DirectoryIterator(APP_ROOT . '/models') as $fileInfo)
			if (!$fileInfo->isDot())
				self::$models[ucwords(chop($fileInfo->getFilename()), '.php')] = null;
	}

	public static function start() {
		self::getAllControllers();
		self::getAllModels();
		list(self::$controller, $method, $params) = Request::processRequest();
		Session::init();
		self::dispatch($method, $params);
		self::stop();
	}

	public static function stop() {
		Session::process();
		Response::send();
		die();
	}

	public static function dispatch($method, $params = []) {
		$controllerDirectory = APP_ROOT . '/controllers/' . self::$controller . '/';
		$controller = ucwords(self::$controller);
		if (key_exists($controller, self::$controllers)) {
			require_once $controllerDirectory . '/' . $controller . '.php';
			if (method_exists($controller, $method)) {
				$controller = new $controller();
				if (!is_callable([$controller, $method])) Response::fatal();
				else call_user_func_array([$controller, $method], $params);
			} else {
				$method = ucwords($method);
				if (file_exists($controllerDirectory . '/methods/' . $method . '.php')) {
					require_once $controllerDirectory . '/methods/' . $method . '.php';
					try {
						$reflect = new ReflectionClass(ucwords(self::$controller) . '\\' . $method);
						$reflect->newInstanceArgs($params);
					} catch (ReflectionException $e) {
						Response::fatal();
					}
				} else Response::fatal();
			}
		} else Response::fatal();
	}

	public static function getModel($model) {
		$model = ucwords($model);
		if (key_exists($model, self::$models)) {
			require_once APP_ROOT . '/models/' . $model . '.php';
			return new $model();
		} else Response::fatal('Model doesn\'t exist');
		return null;
	}
}