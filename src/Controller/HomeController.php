<?php

namespace App\Controller;

use App\core\Controller;
use App\Manager\PostManager;

class HomeController extends Controller
{

    /**
     * @var PostManager
     */
    private $postManager;

    public function __construct()
    {
        parent::__construct();

        $this->postManager = new PostManager();
    }

    public function Home()
    {
        $posts = $this->postManager->getPosts(3, 0);

        return $this->render(
            'home/index.twig',
            ['posts' => $posts]
        );
    }
}
