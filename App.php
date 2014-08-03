<?php

/**
 * Description of App
 *
 * @author iqnev
 */

namespace TG;

include_once 'ClassLoader.php';

class App
{

    private static $_instance = null;
    private $_config = null;
    private $_frontController = null;
    private $router = null;
    private $dbConnection = [];
    private $_session = null;

    /*
     * @return \TG\App
     */

    private function __construct()
    {
        \TG\ClassLoader::registerNamespace('TG', dirname(__FILE__) . DIRECTORY_SEPARATOR);
        \TG\ClassLoader::registerAutoLoad();
        $this->_config = \TG\Config::getInstance();       

        if ($this->_config->getConfigPath() == null) { 
            $this->setConfigFolder('../config');
        }
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function setRouter($router)
    {
        $this->router = $router;
    }

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \TG\App();
        }

        return self::$_instance;
    }

    public function setConfigFolder($path)
    {  
        $this->_config->load($path);
    }

    public function getConfigFolder()
    {
        return $this->_configPath;
    }

    /**
     * 
     * @return \TG\Config
     */
    public function getConfig()
    {
        return $this->_config;
    }

    public function run()
    {
        if ($this->_config->getConfigPath() == null) {
            $this->setConfigFolder('../config');
        }
        $this->_frontController = \TG\FrontController::getInstance();

        if ($this->router instanceof \TG\Routing\iRouter) {
            $this->_frontController->setRouter($this->router);
        } else if ($this->router == 'jsonRPCRouter') {
            //TODO
            $this->_frontController->setRouter(new \TG\Routing\DefaultRouter());
        } else if ($this->router == 'CLIRouter') {
            //TODO
            $this->_frontController->setRouter(new \TG\Routing\DefaultRouter());
        } else {
            $this->_frontController->setRouter(new \TG\Routing\DefaultRouter());
        }

        $session = $this->_config->app['session'];
        if ($session['autostart']) {            
            if($session['type'] == 'native') { 
                $s = new \TG\Sessions\NativeSession($session['name'], $session['expiration'], $session['path'], $session['domain'], $session['security']);
            } elseif ($session['type'] == 'database') {               
                $s = new \TG\Sessions\DbSession($session['dbConnection'], $session['name'], $session['dbTable'], $session['expiration'], $session['path'], $session['domain'], $session['security']);
            } else { 
                throw new Exception('No valid Session', 500);
            }
            $this->setSession($s);
        }

        $this->_frontController->dispach();
    }

    public function setSession(\TG\Sessions\iSession $session)
    {
        $this->_session = $session;
    }

    /**
     * 
     * @return \TG\Sessions\iSession
     */
    public function getSession()
    {
        return $this->_session;
    }

    public function getDbConnection($connection = 'default')
    {
        if (!$connection) {
            throw new \Exception('No connection provider', 500);
        }
        if ($this->dbConnection[$connection]) {
            return $this->dbConnection[$connection];
        }

        $dbConn = $this->getConfig()->database;
        if (!$dbConn[$connection]) {
            throw new \Exception('Invalid connection provider', 500);
        }

        $db = new \PDO($dbConn[$connection]['connection_uri'], $dbConn[$connection]['username'], $dbConn[$connection]['password'], $dbConn[$connection]['pdo_options']);
        $this->dbConnection[$connection] = $db;

        return $db;
    }
    
    public function __destruct()
    {
        if($this->_session) {  
            $this->_session->saveSession();
        }
    }
}
