<?php

defined('_INDEX_EXEC') or die('Restricted access');

// noting time of request
define('REQUEST_TIME', time());

// DB params
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', '');

// app root
define('APP_ROOT', dirname(__FILE__));
// URL root
define('URL_ROOT', 'http://localhost/framework');
// site name
define('SITE_NAME', '');

// key used for encryption-decryption
define('CRYPT_KEY', 'rootByDefault');

// email address to send mails
define('SENDING_EMAIL_ID', 'noreply@example.com');