<?php

defined('_INDEX_EXEC') or die('Restricted access');

if (!isset($message) || empty($message)) $message = 'Invalid URL';

require_once APP_ROOT . '/views/inc/header.php';
echo '<p>' . $message . '</p>';
require_once APP_ROOT . '/views/inc/footer.php';