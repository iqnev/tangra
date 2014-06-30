<?php

/**
 * Description of Request
 *
 * @author iqnev
 */

namespace TG\Web;

class Request
{
    private static $_instance = null;
    private $_get = [];
    private $_post = [];
    private $_cookies = [];
    
    private function __construct()
    {
        $this->_cookies = $_COOKIE;
    }
    
    /**
     * 
     * @return TG\Web
     */
    public static function getInstance()
    {
        if(self::$_instance == null) {
            self::$_instance = new \TG\Web\Request();
        }
        
        return self::$_instance;
    }
    
    public function setPost($arr)
    {
        if(is_array($arr)) {
            $this->_post = $arr;
        }
    }
    
    public function setGet($arr)
    {
        if(is_array($arr)) {
            $this->_get = $arr;
        }
    }
    
    public function isPost($name)
    {
        return array_key_exists($name, $this->_post);
    }
    
    public function isCookies () 
    {
        return array_key_exists($name, $this->_cookies);
    }
    
    public function isGet() 
    {
        return array_key_exists($name, $this->_get);
    }
    
    public function get($id, $normalize = null, $default = null)
    {
        if($this->isGet($id)) {
            if($normalize != null) {
                return \TG\Common::normalize($this->_get[$id], $normalize);
            }
            return $this->_get[$id];
        }
        
        return $default;
    }
    
    public function post($name, $normalize = null, $default = null)
    {
        if($this->isPost($name)) {
            if($normalize != null) {
                return \TG\Common::normalize($this->_post[$name], $normalize);
            }
            return $this->_post[$name];
        }
        
        return $default;
    }
    
    public function cookies($name, $normalize = null, $default = null)
    {
        if($this->isCookies($name)) {
            if($normalize != null) {
                return \TG\Common::normalize($this->_cookies[$name], $normalize);
            }
            return $this->_cookies[$name];
        }
        
        return $default;
    }
            
}


