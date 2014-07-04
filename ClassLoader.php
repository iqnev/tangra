<?php

/**
 * Description of ClassLoader
 *
 * @author iqnev
 */

namespace TG;

final class ClassLoader
{

    private static $_namespaces = [];

    private function __construct()
    {
        
    }

    public static function registerAutoLoad()
    {
        spl_autoload_register(['\TG\ClassLoader', 'autoload']);
    }

    public static function autoload($class)
    {      
        foreach (self::$_namespaces as $key => $val) {  
            if (strpos($class, $key) === 0) { 
                $file = realpath(substr_replace(str_replace('\\', DIRECTORY_SEPARATOR, $class), $val, 0, strlen($key)) . '.php');       //  echo '<pre>'; var_dump($file);               
                if ($file && is_readable($file)) { 
                    include $file;
                } else {
                    /*
                     * @todo make global error 
                     */
                    throw new \Exception('File cannot be include:' . $file);
                }  
                break;
            }        
        }
    }

    public static function registerNamespace($namespace, $path)
    {  
        $namespace = trim($namespace);
        if (strlen($namespace) > 0) { 
            if (!$path) {
                /*
                 * @todo make global error 
                 */
                throw new \Exception('Invalid path');
            }
            $_path = realpath($path); 
            if ($_path && is_dir($_path) && is_readable($_path)) { 
                self::$_namespaces[$namespace] = $_path . DIRECTORY_SEPARATOR;            
            } else {
                /*
                 * @todo make global error 
                 */
                throw new \Exception('Namespace error:' . $path);
            }
        } else {
            /*
             * @todo make global error 
             */
            throw new \Exception('Invalid namespace:' . $namespace);
        }
    }
    
    public static function registerNamespaces($arg)
    {         
        if(is_array($arg)) { 
            foreach ($arg as $k => $val) { ;
                self::registerNamespace($k, $val);
            }
        } else {
            throw new \Exception('Invalid namespaces');
        }
    }

}

