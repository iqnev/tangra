<?php
/**
 * Created by PhpStorm.
 * User: iqnev
 * Date: 9/7/14
 * Time: 8:24 PM
 */

namespace TG;


class Validator {

    private $_rules = [];
    private $_errors = [];

    public function rule($rule, $value, $params = null, $name = null)
    {
        $this->_rules[] = ['rule' => $rule, 'value' => $value, 'params' => $params, 'name' => $name];

        return $this;
    }

    public function validate()
    {
        $this->_errors = [];

        if(count($this->_rules) >0) {
            foreach($this->_rules as $rul) {
                if(!$this->$rul['rule'] ($rul['value'], $rul['params'])) {
                    if($rul['name']) {
                        $this->_errors[] = $rul['name'];
                    } else {
                        $this->_errors[] = $rul['rule'];
                    }
                }
            }
        }

        return (bool) !count($this->_errors);
    }

    public function getErrors()
    {
        return $this->_errors;
    }

    public function __call($a, $b)
    {
        throw new \Exception('Invalid validation rule', 500);
    }

    public function custom($val, $funct)
    {
        if($funct instanceof \Closure) {
            return (boolean) call_user_func($funct, $val);
        } else {
            throw new \Exception('Invalid validation rule', 500);
        }

    }

    public static function matches($value, $subject )
    {
        return $value == $subject;
    }

    public static function minLenght($value, $size)
    {
        return (mb_strlen($value, 'UTF-8') >= $size);
    }
} 