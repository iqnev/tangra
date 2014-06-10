<?php

/**
 * Description of App
 *
 * @author iqnev
 */
namespace TG;
use TG\App;

include_once 'ClassLoader.php';
class App {
    
    private static $_instance = null;
    private $_config = null;


    /*
     * @return \TG\App
     */
    private function __construct()
    {
        \TG\ClassLoader::registerNamespace('TG', dirname(__FILE__).DIRECTORY_SEPARATOR);
        \TG\ClassLoader::registerAutoLoad();
        $this->_config = \TG\Config::getInstance();
        
        if($this->_config->getConfigPath() == null) {
           $this->setConfigFolder('../config');
       }
    }

    public static function getInstance()
    {
        if(self::$_instance == null) {
            self::$_instance = new App();
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
       if($this->_config->getConfigPath() == null) {
           $this->setConfigFolder('../config');
       }
    }
}
