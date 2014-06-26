<?php

/**
 * Description of Common
 *
 * @author iqnev
 */

namespace TG;

class Common
{

    static function normalize($data, $rule)
    {
        $rule = explode('|', $rule);

        if (is_array($rule)) {
            //TODO add more rules
            foreach ($rule as $t) {
                if ($t == 'int') {
                    $data = (int) $data;
                }
                if ($t == 'bool') {
                    $data = (bool) $data;
                }
                if ($t == 'string') {
                    $data = (string) $data;
                }
                if ($t == 'trim') {
                    $data = trim($data);
                }
            }
        }

        return $data;
    }

}
