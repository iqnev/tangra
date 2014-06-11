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

    private function __construct()
    {
        
    }

    public function dispach()
    {
        
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
