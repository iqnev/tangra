<?php

/**
 * Description of Config
 *
 * @author iqnev
 */
/**
 * @namespace
 */

namespace TG;

class Config
{

    private static $_instance = null;
    private $_configPath = null;
    private $_configArray = [];

    private function __construct()
    {
        
    }

    /**
     * 
     * @return \TG\Congig
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \TG\Config();
        }

        return self::$_instance;
    }

    /**
     * 
     * @return string
     */
    public function getConfigPath()
    {
        return $this->_configPath;
    }

    public function __get($name)
    {
        if (!$this->_configArray[$name]) {
            $this->loadConfigFile($this->_configPath . $name . '.php');
        }
        if (array_key_exists($name, $this->_configArray)) {
            return $this->_configArray[$name];
        }
        return null;
    }

    /**
     * setup path to configuration files
     * 
     * @param $configPath
     */
    public function load($configPath)
    {
        if (!$configPath) {
            throw new Exception('Empty config folder path');
        }

        $_configPath = realpath($configPath);
        if ($_configPath != false && is_readable($_configPath) && is_dir($_configPath)) {
            $this->_configArray = [];
            $this->_configPath = $_configPath . DIRECTORY_SEPARATOR;
        } else {
            throw new Exception('Configuration directory is not correct' . $_configPath);
        }
    }

    /**
     * 
     * @param type $path
     */
    public function loadConfigFile($path)
    {
        if (!$path) {
            throw new \Exception;
        }

        $file = realpath($path);
        if ($file != FALSE && is_file($file) && is_readable($file)) {
            $basename = explode('.php', basename($file))[0];
            $this->_configArray[$basename] = include $file;
            $namespace = $this->app['namespace'];
            if (is_array($namespace)) {
                \TG\ClassLoader::registerNamespace($namespace);
            }
        } else {
            throw new Exception('Configuration file is not correct' . $path);
        }
    }

}
