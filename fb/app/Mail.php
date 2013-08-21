<?php

class Mail
{
    private $mail_to = '';
    private $mail_from = 'name@domain.zone';
    private static $mail_native = true;
    public $setting;

    function __construct()
    {
        $this->setting = new MailPhp();
    }

    public function send($title, $body)
    {
        if (self::$mail_native) {
            $mail = new MailNative();
        } else {
            $mail = $this->setting;
        }
        $mail_send = $mail->send($this->mail_to, $title, $body, $this->mail_from);
        return $mail_send;
    }

    public function _to($var)
    {
        $this->mail_to = $var;
    }

    public function getMailTo()
    {
        return $this->mail_to;
    }    

    public function _from($mail, $name = '')
    {
        if ($name == '') $mail = $mail.' <'.$mail.'>';
        else $mail = $name.' <'.$mail.'>';

        $this->mail_from = $mail;
    }

    public function nativeMail($var = true)
    {
        self::$mail_native = $var;
    }

    public function __get($var)
    {
    }

    public function __set($var, $val)
    {
    }
}

