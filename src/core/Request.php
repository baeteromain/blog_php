<?php

namespace App\core;

class Request
{
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
        $this->files = $_FILES;
        $this->session = new Session();
    }

    public function getGet($params = null)
    {
        return filter_input(INPUT_GET, $params);
    }

    public function getGetAll()
    {
        return filter_input_array(INPUT_GET);
    }

    public function getPost($params = null)
    {
        return filter_input(INPUT_POST, $params);
    }

    public function getPostAll()
    {
        return filter_input_array(INPUT_POST);
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
