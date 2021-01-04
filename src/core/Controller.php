<?php 
namespace App\core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;


abstract class Controller {

    protected $twig;

    public function __construct()
    {
        $this->getTwig();
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
    }

    public function render($template, $options = [])
    {
        echo $this->twig->render($template, $options);
    }
}