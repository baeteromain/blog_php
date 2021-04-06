<?php

namespace App\core;

class Request
{
    private $get;
    private $post;
    private $files;
    private $session;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->session = new Session($_SESSION);
    }

    public function getGet()
    {
        return $this->get;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getFiles()
    {
        return $this->files;
    }
}
