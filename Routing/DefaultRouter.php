<?php

/**
 * Description of DefaultRouter
 *
 * @author iqnev
 */

namespace TG\Routing;

class DefaultRouter implements \TG\Routing\iRouter
{
    /**
     * 
     * @return string
     */
    public function getURI()
    {
        return substr($_SERVER['PHP_SELF'], strlen($_SERVER['SCRIPT_NAME']) + 1);
    }

    public function getPost()
    {
        return $_POST;
    }
}

