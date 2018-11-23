<?php

namespace Pages;

defined('_INDEX_EXEC') or die('Restricted access');

class About extends \Pages {
	public function __construct() {
		parent::__construct();
		\Response::view('about', ['key' => 'value']);
	}
}