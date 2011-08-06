<?php
// start output buffering
ob_start();

// set our app paths and environments
define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
define('APPLICATION_PATH', BASE_PATH . '/application');
define('APPLICATION_ENV', 'staging');

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
	realpath(APPLICATION_PATH . '/tests'),
	realpath(APPLICATION_PATH . '/../library'),
	realpath(APPLICATION_PATH . '/models'),
	get_include_path(),
)));
// We wanna catch all errors en strict warnings
error_reporting(E_ALL|E_STRICT);

require_once 'Zend/Application.php';

$application = new Zend_Application(
	APPLICATION_ENV,
	APPLICATION_PATH . '/configs/config.ini'
);
$application->bootstrap();

require_once 'ControllerTestCase.php';
