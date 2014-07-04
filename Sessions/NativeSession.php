<?php


/**
 * Description of NativeSession
 *
 * @author iqnev
 */
namespace TG\Sessions;

class NativeSession implements \TG\Sessions\iSession
{
    public function __construct($name, $expiration = 3600, $path = null, $domain = null, $security = false)
    {
        if(strlen($name) < 1) {
            $name = 'tg_sess';
        }
        session_name($name);
        session_set_cookie_params($expiration, $name, $domain, $security, true);
        session_start();
    }

    public function __get($name)
    {
        return $_SESSION[$name];
    }

    public function __set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public function getSessionId()
    {
        return session_id();
    }

    public function removeSession()
    {
        session_destroy();
    }

    public function saveSession()
    {
        session_write_close();
    }    
}
