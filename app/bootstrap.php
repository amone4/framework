<?php

defined('_INDEX_EXEC') or die('Restricted access');

// defining timezone
date_default_timezone_set('Asia/Kolkata');

// starting session
session_start();

// load constants
require_once '../app/config.php';

// loading libraries
foreach (new DirectoryIterator('../app/libraries') as $fileInfo)
	if (!$fileInfo->isDot())
		require_once '../app/libraries/' . $fileInfo->getFilename();

// loading helpers
foreach (new DirectoryIterator('../app/helpers') as $fileInfo)
	if (!$fileInfo->isDot())
		require_once '../app/helpers/' . $fileInfo->getFilename();