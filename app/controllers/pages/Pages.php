<?php

defined('_INDEX_EXEC') or die('Restricted access');

class Pages {
	public function __construct() {
		echo 'Hello world<br>';
	}

	public function index() {
		Response::success('Welcome');
		Response::view('index', ['key' => 'value']);
	}
}