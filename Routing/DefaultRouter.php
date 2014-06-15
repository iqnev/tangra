<?php

/**
 * Description of DefaultRouter
 *
 * @author iqnev
 */

namespace TG\Routing;

class DefaultRouter
{

    private $controller = null;
    private $method = null;
    private $params = [];

    /**
     * 
     * @return void
     */
    public function parse()
    {
        $resourceUri = substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME']) + 1);
        $_params = explode('/', $resourceUri);
        if ($_params[0]) {
            $this->controller .= ucfirst($_params[0]);

            if ($_params[1]) {
                $this->method = $_params[1];
                unset($_params[0], $_params[1]);
            }
        }
    }

    /**
     * 
     * @return stting
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * 
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * 
     * @return array
     */
    public function getParamsGet()
    {
        return $this->params;
    }

}

