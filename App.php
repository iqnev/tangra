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
    
    /*
     * @return \TG\App
     */
    private function __construct()
    {
        \TG\ClassLoader::registerNamespace('TG', dirname(__FILE__).DIRECTORY_SEPARATOR);
        \TG\ClassLoader::registerAutoLoad();
    }

    public static function getInstance()
    {
        if(self::$_instance == null) {
            self::$_instance = new App();
        }
        
        return self::$_instance;
    }
    
    public function run()
    {
       
    }
}
