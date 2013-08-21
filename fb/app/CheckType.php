<?php

class CheckType
{
    public static function checkNumb($val) 
    {
        if (is_numeric($val) || $val == '') {
            return true;
        }
        return false;
    }

    public static function checkEmail($var) 
    {
        $patt = "/^([\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+\.)*[\w\!\#$\%\&\'\*\+\-\/\=\?\^\`{\|\}\~]+@((((([a-z0-9]{1}[a-z0-9\-]{0,62}[a-z0-9]{1})|[a-z])\.)+[a-z]{2,6})|(\d{1,3}\.){3}\d{1,3}(\:\d{1,5})?)$/i";
        if ($var == '') return true;
        if (preg_match($patt, $var)) return true;
        return false;
    }    
}
