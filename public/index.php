<?php

require_once '../vendor/autoload.php';

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../vendor/shardj/zf1-future/library'),
    get_include_path(),
)));

// Load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(APPLICATION_PATH . '/../');
$dotenv->load();

// Read the contents of the application.ini file
$applicationIniPath = APPLICATION_PATH . '/configs/application.ini';
$applicationIniContents = file_get_contents($applicationIniPath);

// Replace the placeholders with the environment variables
$applicationIniContents = str_replace(
    ['%DB_HOST%', '%DB_USER%', '%DB_PASS%', '%DB_NAME%'],
    [$_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'],$_ENV['DB_NAME']],
    $applicationIniContents
);

// Save the updated contents to a temporary file
$tempFile = tempnam(sys_get_temp_dir(), 'application_ini_');
file_put_contents($tempFile, $applicationIniContents);

// Create a new Zend_Config_Ini instance using the updated contents
$config = new Zend_Config_Ini($tempFile, APPLICATION_ENV);

// Remove the temporary file
unlink($tempFile);

Zend_Registry::set('config', $config);

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    Zend_Registry::get('config')
);
$application->bootstrap()
    ->run();
