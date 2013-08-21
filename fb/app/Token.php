<?php

class Token
{
    private $config;

    function __construct()
    {
        $this->config = Config::get();        
    }
    
    public function gen()
    {
        if (isset($_SESSION)) $sess_id = session_id();
        else $sess_id = '';

        $data = $sess_id.Ip::get().UserAgent::get();
        $salt = site_path.hash('md5', $this->config['token_salt']);
        $token = hash ('sha256', $data.$salt);
        return $token;
    }

    public function check()
    {
        if ($this->config['token']) {
                $token = GetInp::gp('fb_token');
                $token_gen = $this->gen();
                if ($token != $token_gen)   die ('{"status" : "2", "msg" : "Token is wrong!"}');
        }
    }
}
