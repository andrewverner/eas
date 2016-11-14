<?php

/**
 * Created by PhpStorm.
 * User: dkhodakovskiy
 * Date: 14.11.16
 * Time: 16:10
 */
class EveCRESTRequest
{

    public $url;
    public $post = false;
    public $authType = 'Bearer';
    public $oauth = false;
    public $postData = false;
    public $contentType = 'application/vnd.ccp.eve.Api-v3+json; charset=utf-8';

    private $secret = 'JSt85YT7p8w3B36sTvQfzCX37TIi4JDabNNOd6iQ';
    private $appid = '862b3aa0e295461f8f2fdaaf3055c3f4';
    private $authCode;

    public function __construct($url)
    {
        $this->authCode = base64_encode("{$this->appid}:{$this->secret}");
        $this->url = $url;
        return $this;
    }

    public function post()
    {
        $this->post = true;
        return $this;
    }

    public function postData($data)
    {
        $this->postData = $data;
        return $this;
    }

    public function authBasic()
    {
        $this->authType = 'Basic';
        return $this;
    }

    public function contentType($type)
    {
        $this->contentType = $type;
        return $this;
    }

    public function send()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_POST, $this->post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: {$this->authType} $this->authCode",
            "Content-Type: $this->contentType",
            'Host: login.eveonline.com'
        ]);
        if ($this->postData) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postData);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        return curl_exec($ch);
    }

}
