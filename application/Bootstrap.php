<?php

require_once '../vendor/autoload.php';

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initEnvironment()
    {
        // Load the .env file
        $dotenv = Dotenv\Dotenv::createImmutable(APPLICATION_PATH . '/../');
        $dotenv->load();

        // Set the variables from the environment to the Zend_Registry
        Zend_Registry::set('dbHost', $_ENV['DB_HOST']);
        Zend_Registry::set('dbUser', $_ENV['DB_USER']);
        Zend_Registry::set('dbPass', $_ENV['DB_PASS']);
        Zend_Registry::set('dbName', $_ENV['DB_NAME']);
        Zend_Registry::set('dbPort', $_ENV['DB_PORT']);
    }

    protected function _initConfig()
    {
        $this->bootstrap('environment');
        $config = new Zend_Config($this->getOptions(), true);

        $dotenv = Dotenv\Dotenv::createImmutable(APPLICATION_PATH . '/../');
        $dotenv->load();

        $dbParams = [
            'host'     => $_ENV['DB_HOST'],
            'username' => $_ENV['DB_USER'],
            'password' => $_ENV['DB_PASS'],
            'dbname'   => $_ENV['DB_NAME'],
        ];

        $newConfigArray = ['resources' => ['db' => ['params' => $dbParams]]];
        $newConfig = new Zend_Config($newConfigArray, true);
        $config->merge($newConfig);

        Zend_Registry::set('config', $config);
        return $config;
    }

    protected function _initRoutes()
    {
        $router = Zend_Controller_Front::getInstance()->getRouter();

        $sentRoute = new Zend_Controller_Router_Route(
            'sent',
            array(
                'controller' => 'index',
                'action'     => 'sent'
            )
        );

        $router->addRoute('sent', $sentRoute);
    }
}
