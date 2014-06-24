<?php

/**
 * Description of FrontController
 *
 * @author iqnev
 */

namespace TG;

use TG\FrontController;

class FrontController
{

    private static $_instance = null;
    private $namespace = null;
    private $controller = null;
    private $method = null;
    private $router;
    
    public function getRouter()
    {
        return $this->router;
    }

    public function setRouter(\TG\Routing\iRouter $router)
    {
        $this->router = $router;
    }

        private function __construct()
    {
        
    }

    public function dispach()
    {
        if($this->router == null) {
            throw new \Exception('No router found', 500);
        }      
        $_uri = $this->router->getURI();
        $router = new \TG\Routing\DefaultRouter();
        $routes = \TG\App::getInstance()->getConfig()->routes;
        $_cRewrite = null;
        if (is_array($routes) && count($routes) > 0) {  
            foreach ($routes as $k => $val) {
                if (strpos($_uri, $k) === 0 && ($_uri == $k || strpos($_uri, $k . '/') === 0) && $val['namespace']) {
                    $this->namespace = $val['namespace']; 
                    $_uri = substr($_uri, strlen($k)+1); 
                    $_cRewrite = $val;
                    break;
                }
            }
        } else {
            throw new \Exception('Route missing', 500);
        }

        if ($this->namespace == null && $routes['*']['namespace']) {
            $this->namespace = $routes['*']['namespace'];
            $_cRewrite = $routes['*'];
        } elseif ($this->namespace == null && !$routes['*']['namespace']) {
            throw new \Exception('Default route missing', 500);
        }

        $_params = explode('/', $_uri);       
        if ($_params[0]) {
            $this->controller = strtolower($_params[0]);

            if ($_params[1]) {
                $this->method = strtolower($_params[1]);
            } else {
                $this->method = $this->getDefaultMethod();
            }
        } else { 
            $this->controller = $this->getDefaultController();
            $this->method = $this->getDefaultMethod();
        }
        if(is_array($_cRewrite) && $_cRewrite['controllers']) {
            if( $_cRewrite['controllers'][$this->controller]['methods'][$this->method]) {
                $this->method =  strtolower($_cRewrite['controllers'][$this->controller]['methods'][$this->method]);
            }
            if(isset($_cRewrite['controllers'][$this->controller]['to'])) {
                $this->controller = strtolower($_cRewrite['controllers'][$this->controller]['to']);
            }
        }
        
        $def = $this->namespace . '\\' . ucfirst($this->controller);
        $newController = new $def();
        $newController-> {$this->method}();

    }
    
    /**
     * 
     * @return string
     */
    public function getDefaultController()
    {
        $controller = \TG\App::getInstance()->getConfig()->app['default_controller'];        
        if($controller) {
            return strtolower($controller);
        }
        return 'index';
    }
    
    /**
     * 
     * @return string
     */
    public function getDefaultMethod() {
        $method = \TG\App::getInstance()->getConfig()->app['default_method'];
        if($method) {
            return strtolower($method);
        }
        return 'index';
    }

    /**
     * 
     * @return TG\FrontController
     */
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new FrontController();
        }

        return self::$_instance;
    }

}
