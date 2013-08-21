<?php
header("Content-Type: text/html; charset=utf-8");

class Fb
{
    public $mail;
    private $checkpost;
    private $file;
    private $file_data;    
    private $vars_tmpl = Array();
    private $vars_form = Array();
    private $vars_mail = Array();
    private $vars_post = Array();
    private $form_setting = Array();
    private static $post_data = false;
    private static $disp_form = false;
    private static $captcha = false;
    private static $css_pos = 'Left';
    const ERR_MSG = 'Wrong!';
    const MSG_SENT = 'Message was sent!';

    function __construct() 
    {
        $this->mail = new Mail();
        $this->checkpost = new CheckPost();

        $this->form_setting['vars_form'] = Array();
        $this->form_setting['captcha'] = self::$captcha;        
        $this->form_setting['action'] = '';        
        $this->form_setting['position'] = self::$css_pos;        

        $this->setFile();
        $this->getNames();
    }

    private function setFile() 
    {
        $file = site_path.DIRSEP.'templ_mail.php';
        if (file_exists($file)) $this->file = $file;
        else $this->file = '';
        $this->getFile();
    }

    private function getFile() 
    {
        $this->file_data = file_get_contents($this->file);
    }

    private function getNames() 
    {
        preg_match_all ('/{(.*)}/sUS', $this->file_data, $match);        

        $match[1] = array_unique($match[1]);      
        $this->vars_tmpl = $match[1];
    }

    private function getPostNames() 
    {
        $err = Array();
        $new_form = Array();
        // sorting
        foreach ($this->vars_form as $key_f => $val_f) {
            foreach ($this->vars_tmpl as $key => $val) {
                if ($key_f == $val) {
                    $new_form[] = $val;
                }
            }
        }
        $diff = array_diff($this->vars_tmpl,$new_form);
        if (!empty($diff)) {
            $new_form = array_merge($new_form, $diff);
        }

        foreach ($new_form as $key => $val) {
            $post_val = GetInp::has($val);            
            if ($post_val) {
                self::$post_data = true;
                $post_val = GetInp::gp($val);                
                if ($val == 'emailto') $this->mail->_to($post_val);
                if (isset($this->vars_form[$val])) {
                    $check = $this->checkpost->checkPostVar($this->vars_form[$val], $post_val);
                    if (!$check) $err[$val] = $this->vars_form[$val]['error'];       
                    else $this->vars_mail[$val] = $post_val;
                } else {
                    $this->vars_mail[$val] = $post_val;
                }
            }
        }

        if (self::$captcha) {
            $captcha = GetInp::has('captchakeystring');
            if (self::$post_data && $captcha) {
                $captcha = GetInp::gp('captchakeystring');
	            if(isset($_SESSION['captcha_keystring']) && $_SESSION['captcha_keystring'] === $captcha){
                    //$err['captchakeystring'] = 'corr';
	            }else{
                    $err['captchakeystring'] = 'Wrong!';
	            }
            }
            unset($_SESSION['captcha_keystring']);
        }

        if (empty($err) && self::$post_data) {
            $fb_fb = GetInp::has('fb_fb');
            if ($fb_fb) {
                $token = new Token();
                $token->check();
            }
            $this->prepMail();
        }

        if (self::$post_data) {
            $this->msg($err);
        }
    }

    private function prepMail() 
    {
        $text = $this->file_data;
        foreach ($this->vars_mail as $key => $val) {
            $text = str_replace ("{".$key."}", $val, $text);
        }

        $title = preg_match("/<!--\s*title begin\s*-->(.*)<!--\s*title end\s*-->/sUS", $text, $matches);
        if ($title) {
            $title = $matches[1];
            $title = trim($title);
        } else $title = '';
        $body = preg_match("/<!--\s*body begin\s*-->(.*)<!--\s*body end\s*-->/sUS", $text, $matches);
        if ($body) {
            $body = $matches[1];
            //$body = trim($body);            
        } else $body = '';        

        if ($this->mail->getMailTo() == '') {
            $this->msg('Error: MailTo is Wrong!');
        } else {
            $mail_send = $this->mail->send($title, $body);
            if ($mail_send != '') $this->msg($mail_send);
        }
    }

    private function msg($err) 
    {
        if (is_array($err)) {
            if (!empty($err)) {
                $err = json_encode($err);
                die ('{"status" : "0", "names" : '.$err.'}');
            } else {
                die ('{"status" : "1", "msg" : "'.self::MSG_SENT.'"}');
            }
        } else {
            die ('{"status" : "3", "msg" : "'.$err.'"}');
        }
    }

    public function finish() 
    {
        $this->getPostNames();
        if (!self::$post_data && self::$disp_form) {
            $this->form_setting['vars_form'] = $this->vars_form;
            $this->form_setting['captcha'] = self::$captcha;        
            $form = new Form($this->form_setting);
            echo $form->create();
        }

    }

    public function addName ($name, $type = '', $err = '', $request = 0, $max_len = 0, $min_len = 0, $type_html = 'text') 
    {
        $type = $this->checkpost->types($type);
        $max_len = $this->checkpost->maxLen($max_len);
        $min_len = $this->checkpost->maxLen($min_len);
        $request = $this->checkpost->request($request);
        if ($err == '') $err = self::ERR_MSG;

        $title = '';
        if (is_array($type_html)) {
            if (isset($type_html['title'])) {
                $title = $type_html['title'];
                unset ($type_html['title']);
            }
        }

        $this->vars_form[$name] = Array (
                                    'type' => $type,
                                    'error' => $err,
                                    'request' => $request,
                                    'max_len' => $max_len,
                                    'min_len' => $min_len,
                                    'type_html' => $type_html,
                                    'title' => $title,
                                    );

    }

    public function positionForm($var)
    {
        $positions = Array ('Right', 'Left');
        $var = ucfirst(strtolower($var));
        if (in_array($var, $positions)) $pos = $var;
        else $pos = self::$css_pos;
        $this->form_setting['position'] = $pos;        
    }

    public function displayForm($val = '')
    {
        $this->form_setting['action'] = $val;        
        self::$disp_form = true;
        //if ($val) 
        //else self::$disp_form = false; 
    }

    public function captcha($val = true)
    {
        if ($val) self::$captcha = true;
        else self::$captcha = false; 
    }
}

