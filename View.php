<?php

/**
 * Description of View
 *
 * @author iqnev
 */

namespace TG;

class View {

    private static $_instance = null;
    private $__viewPath = null;
    private $__viewDir = null;
    private $__layoutPath = [];
    private $__data = [];
    private $__layoutData = [];
    private $__extension = '.php';

    private function __construct()
    {
        $this->__viewPath = \TG\App::getInstance()->getConfig()->app['viewDir'];
        if ($this->__viewPath == null) {
            $this->__viewPath = realpath('../views/');
        }
    }

    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new \TG\View();
        }

        return self::$_instance;
    }

    public function __set($name, $value)
    {
        $this->__data[$name] = $value;
    }

    public function __get($name)
    {
        return $this->__data[$name];
    }

    public function setViewDir($path)
    {
        $path = trim($path);
        if ($path) {
            $path = realpath($path) . DIRECTORY_SEPARATOR;
            if (is_dir($path) && is_readable($path)) {
                $this->__viewDir = $path;
            } else {
                //TODO
                throw new Exception('view path', 500);
            }
        } else {
            //TODO
            throw new Exception('view path', 500);
        }
    }

    public function render($view, $data = [], $return = false)
    {
        if (is_array($data)) {
            $this->__data = array_merge($this->__data, $data);
        }
       
        if(count($this->__layoutPath) > 0) { 
            foreach ($this->__layoutPath as $k => $v) {
                $res = $this->includeFile($v);
                if($res) {
                    $this->__layoutData[$k] = $res;
                }
            }
        }
        
        if ($return) {
            return $this->includeFile($view);
        } else {
            echo $this->includeFile($view);
        }
    }
    
    public function getLayout($name) 
    {      
        return $this->__layoutData[$name];
    }
    
    public function setLayout($key, $template)
    { 
        if($key && $template) {
            $this->__layoutPath[$key] = $template;          
        } else {
             throw new \Exception('Layout require valid key and template', 500);
        }
    }

    private function includeFile($file)
    {
        if ($this->__viewDir == null) {
            $this->setViewDir($this->__viewPath);
        }

        $__fullPath = $this->__viewDir . str_replace('.' , DIRECTORY_SEPARATOR, $file) . $this->__extension;        
        if (file_exists($__fullPath) && is_readable($__fullPath)) {
            ob_start();
            include $__fullPath;
            return ob_get_clean();
        } else {
            throw new \Exception('View' . $file . ' cannot be included', 500);
        }
    }

}
