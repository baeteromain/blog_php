<?php

namespace App\core;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected $twig;
    protected $post;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $request = new Request();
        $this->get = $request->getGet();
        $this->post = $request->getPost();
        $this->session = $request->getSession();

        $this->getTwig();
        // $this->post = filter_input_array(INPUT_POST);
    }

    public function getTwig()
    {
        $loader = new FilesystemLoader('../templates');
        $this->twig = new Environment($loader, [
            //TODO: activate cache in production
            //'cache' => '/path/to/compilation_cache',
            //TODO: disable debug in production
            'debug' => true,
        ]);
        $this->twig->addExtension(new DebugExtension());
        $this->twig->addGlobal('session', $this->session);
    }

    public function render($template, $options = [])
    {
        echo $this->twig->render($template, $options);
    }

    public function renderMail($template, $options = [])
    {
        return $this->twig->render($template, $options);
    }
}
