<?php
require_once "mailphp".DIRSEP."mailm.php";
require_once "mailphp".DIRSEP."sasl".DIRSEP."sasl.php";

class MailPhp
{
    private $smtp;

    function __construct ()
    {
        $this->smtp = new mailm();
    	$this->smtp->host_name="smtp.gmail.com";  /* Change this variable to the address of the SMTP server to relay, like "smtp.myisp.com" */
        $this->smtp->host_port=25;  /* Change this variable to the port of the SMTP server to use, like 465 */
        $this->smtp->ssl=0;   /* OpenSSL module is required (http://www.php.net/openssl). Change this variable if the SMTP server requires an secure connection using SSL */
        $this->smtp->start_tls=0;   /* Change this variable if the SMTP server requires security by starting TLS 
	                                       Set to 0 to use the same defined in the timeout variable */
        $this->smtp->debug=0;    /* Set to 1 to output the communication with the SMTP server */
        $this->smtp->html_debug=0;  /* Set to 1 to format the debug output as HTML */

        //$this->smtp->realm="";  /* Set to the authetication realm, usually the authentication user e-mail domain */
        $this->smtp->user="";   /* Set to the user name if the server requires authetication */
        $this->smtp->password="";                 /* Set to the authetication password */
    }

    public function send($to,$subject,$body,$from)
    {
        $from_socket = $this->cut($from);
        $to_socket = $this->cut($to);        

	    if($this->smtp->SendMessage(
		    $from_socket,
		    array(
			    $to_socket
		    ),
		    array(
                "Return-Path: $from",
                "Reply-To: $from",
                "Mime-Version: 1.0",
		        "Content-Type: text/plain; charset=\"UTF-8\"",
                "Content-Transfer-Encoding: 8bit",
			    "From: $from",
			    "To: $to",
                "Subject: $subject",
                "X-mailer: FeedBack 1.0",                
			    "Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")
		    ),
		    $body))
		    return ''; 
	    else {
            return "Mail Error: ".$this->smtp->error;
        }
    }

    private function cut($var)
    {
        $res = preg_match("/<(.*)>/sUS", $var, $matches);
        if ($res) $res = $matches[1];
        else $res = $var;
        return $res;
    }

    public function __get($var)
    {
        $this->smtp->$var;

    }

    public function __set($var, $val)
    {
        $this->smtp->$var = $val;
    }

}
