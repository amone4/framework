<?php defined('_INDEX_EXEC') or die('Restricted access'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<script src="<?php echo URL_ROOT; ?>/public/vendor/jquery/jquery-3.3.1.min.js"></script>
	<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/vendor/bootstrap/css/bootstrap.min.css">
	<script src="<?php echo URL_ROOT; ?>/public/vendor/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/vendor/fontawesome/css/fontawesome-all.min.css">
	<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/vendor/fontawesome/css/fa-svg-with-js.css">
	<script src="<?php echo URL_ROOT; ?>/public/vendor/fontawesome/js/fontawesome-all.min.js"></script>
	<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/style.css">
	<title><?php echo SITE_NAME; ?></title>
</head>
<body>
	<script>const rootURL = '<?php echo URL_ROOT; ?>';</script>

	<?php require_once APP_ROOT . '/views/inc/navbar.php'?>