<?php

class Form
{
    private static $types = Array ('text', 'textarea', 'select');
    private $form_setting;
    private $vars_form;
    private $config;

    function __construct ($form_setting)
    {
        $this->config = Config::get();
        $this->form_setting = $form_setting;
        $this->vars_form = $form_setting['vars_form'];
    }

    public function create()
    {
        //echo $_SERVER['PHP_SELF'];
        //echo __FILE__;
        
        if (fb_path != '') $fb_path = '/'.fb_path.'/';
        else $fb_path = '';



        if ($this->form_setting['captcha']) {
            $captcha = '
                <script type="text/javascript">
                    /* begin reload captcha */
                    function image_reload() {
                        img_captcha = fb_form.find(".fb-img-captcha");
                        img_captcha.attr("src","'.$fb_path.'images/ajax_loader_green.gif");                        
                        img_captcha.attr("src","'.$fb_path.'app/kcaptcha/index.php?'.session_name().'="+Math.random());

                    }
                    /* end reload captcha */
                </script>
            <div class="fb-captcha">
                <div class="fb-captcha-rasp"><img src="'.$fb_path.'app/kcaptcha/index.php?'.session_name().'='.session_id().'" class="fb-img-captcha" ></div>
                <a href="javascript:void(0);" onclick="image_reload();return false;" class="fb-link-captcha">refresh</a>
                <div class="fb-title">Enter text shown above:</div>
                <input type="text" name="captchakeystring">
            </div>';
        } else $captcha = '<br/>';


        if ($this->config['token']) {
            $token = new Token();
            $token_gen = $token->gen();
            $token_gen = '<input type="hidden" name="fb_token" value="'.$token_gen.'" />';
        } else {
            $token_gen = '';
        }

        $html = '
            <link rel="stylesheet" href="'.$fb_path.'css/fb'.$this->form_setting['position'].'.css" type="text/css" />
            <script type="text/javascript" src="'.$fb_path.'js/jquery-1.8.2.min.js"></script>  
            <script type="text/javascript" src="'.$fb_path.'js/jquery-ui-1.9.1.custom.min.js"></script>  
            <script type="text/javascript" src="'.$fb_path.'js/fb.js"></script>  

            <div id="fb-block">
                <a href="javascript:void(0);" id="fb-show"><img src="'.$fb_path.'images/fb_show_emailme_green.gif" border="0" /></a>
                <div class="fb-form">
                <form action="'.$this->form_setting['action'].'" method="post" enctype="multipart/form-data">';
        $html .= $this->createInput();
        $html .= '<input type="hidden" name="fb_fb" value="" />';
        $html .= $captcha.$token_gen.'
                   <br/><br/>
                    <a href="javascript:void(0);" id="fb-button" class="fb-button butt-color">Send!</a>
                    <img src="'.$fb_path.'images/ajax_loader_green.gif" class="fb-ajload" border="0" />
                </form>
                </div>
            </div>';
        
        return $html;
    }

    private function blockInput($name)
    {
        if (isset($this->vars_form[$name]['title']) && ($this->vars_form[$name]['title']!='')) {
            $title = $this->vars_form[$name]['title'];
        } else $title = ucfirst($name);

        $request = $this->vars_form[$name]['request'];
        if ($request) $request = '<span class="fb-nec">*</span>';
        else $request = '';

        $html = '<div class="fb-title">'.$title.': '.$request.'</div>';
        $html .= $this->vars_form[$name]['type_html'];
        return $html;
    }

    private function createInput()
    {
        $html = '';
        foreach ($this->vars_form as $name => $val) {
            $type = $val['type_html'];

            $type_sel = $this->selType($type);
            if ($type_sel != '') {
                $method = 'cr'.ucfirst($type_sel);
                if (method_exists($this, $method)) {
                    $this->vars_form[$name]['type_html'] = $this->$method($name, $type);
                    $html .= $this->blockInput($name);
                }
            }
        }

        return $html;
    }

    private function crText($name, $data)
    {
        $html = '<input type="text" name="'.$name.'" value="" />';
        return $html;
    }

    private function crTextarea($name, $data)
    {
        $html = '<textarea name="'.$name.'"></textarea>';
        return $html;
    }    

    private function crSelect($name, $data)
    {
        $html = '<select name="'.$name.'">';
        foreach ($data as $type => $arr) {
            foreach ($arr as $key => $val) {
                $html .= '<option value="'.$key.'">'.$val.'</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }

    private function selType($type)
    {
        if (is_array($type)) {
            foreach ($type as $key => $val) {
                if (in_array($key, self::$types)) {
                    return $key;
                }
            }
        } else {
            if (in_array($type, self::$types)) {
                return $type;
            }
        }

        return '';
    }

}
