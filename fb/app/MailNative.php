<?php

class MailNative 
{
    private $to = null;
    private $from = null;
    private $subject = null;
    private $body = null;
    private $headers = null;
    const DATA_CHARSET = 'UTF-8';
    const SEND_CHARSET = 'UTF-8';
    //private $send_charset = 'KOI8-R';

    public function send($to, $subject, $body, $from)
    {
        $this->to = $to;
        $this->from = $from;
        $this->subject = $this->MimeHeaderEncode($subject);
        if(self::DATA_CHARSET != self::SEND_CHARSET) $this->body = iconv(self::DATA_CHARSET, self::SEND_CHARSET, $body);
        else $this->body = $body;
        if ($this->sendMail()) return '';
        else return 'Could not send the message. Mail Error!';
    }

    private function sendMail()
    {
        $this->addHeader('Return-Path: '.$this->from."\r\n");
        $this->addHeader('Reply-To: '.$this->from."\r\n");
        $this->addHeader('Mime-Version: 1.0'."\r\n");
        $this->addHeader('Content-Type: text/plain; charset='.self::SEND_CHARSET."\r\n");
        $this->addHeader('Content-Transfer-Encoding: 8bit'."\r\n");
        $this->addHeader('From: '.$this->from."\r\n");
        $this->addHeader('X-mailer: FeedBack 1.0'."\r\n");
        $this->addHeader("Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")."\r\n");
        if (mail($this->to,$this->subject,$this->body,$this->headers)) {
            return true;
        }
        else return false;
    }

    private function addHeader($header)
    {
        $this->headers .= $header;
    }
    
    private function mimeHeaderEncode($str) 
    {
        if (self::DATA_CHARSET != self::SEND_CHARSET) {
            $str = iconv(self::DATA_CHARSET, self::SEND_CHARSET, $str);
        }
        return '=?' . self::SEND_CHARSET . '?B?' . base64_encode($str) . '?=';
    }
}
