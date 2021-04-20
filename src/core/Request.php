<?php

namespace App\core;

class Request
{

    /**
     * @var array
     */
    private $get;
    /**
     * @var array
     */
    private $post;
    /**
     * @var array
     */
    private $files;
    /**
     * @var Session
     */
    private $session;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->files = $_FILES;
        $this->session = new Session($_SESSION);
    }

    public function getGet(): array
    {
        return $this->get;
    }

    public function getPost(): array
    {
        return $this->post;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function getFiles(): array
    {
        return $this->files;
    }
}
