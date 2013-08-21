<?php

class CheckPost
{
    private static $types = Array('numb', 'email');

    public function checkPostVar($var, $val) 
    {
        $type = $var['type'];
        $request = $var['request'];
        $max_len = $var['max_len'];
        $min_len = $var['min_len'];

        if ($type != '') {
            $checktype = 'check'.ucfirst($type);
            $res[] = CheckType::$checktype($val);
        } else $res[] = true;
        
        if ($request) {
            $res[] = $this->checkRequest($val);
        }

        if ($max_len != 0) {
            $res[] = $this->checkMaxLen($max_len, $val);
        }

        if ($min_len != 0) {
            $res[] = $this->checkMinLen($min_len, $val);
        }

        
        if (in_array(false, $res)) $res_r = false;
        else $res_r = true;
        //print_r ($res);

        return $res_r;
    }

    private function checkMaxLen($max_len, $val) 
    {
        if (strlen($val) > $max_len) return false;
        return true;
    }

    private function checkMinLen($min_len, $val) 
    {
        if (strlen($val) < $min_len) return false;
        return true;
    }

    private function checkRequest($val) 
    {
        if ($val == '') return false;
        return true;
    }

    public function types($type) 
    {
        if (in_array($type, self::$types)) return $type;
        return '';
    }

    public function maxLen($max_len) 
    {
        $max_len = intval($max_len);
        if (is_int($max_len)) return $max_len;
        return 0;
    }

    public function request ($req) 
    {
        $res = 0;
        if ($req) $res = 1;

        return $res;
    }
}
