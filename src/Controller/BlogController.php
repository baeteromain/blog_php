<?php

namespace App\Controller;

use App\core\Controller;
use App\Manager\CategoryManager;
use App\Manager\PostManager;

class BlogController extends Controller
{
    private $postManager;
    private $categoryManager;

    public function __construct()
    {
        parent::__construct();

        $this->postManager = new PostManager();
        $this->categoryManager = new CategoryManager();
    }

    public function listPosts()
    {
        $posts = $this->postManager->getPosts();

        foreach ($posts as $post) {
            $categoriesofposts[$post->getId()] = $this->postManager->getCategoryByPost($post->getId());
        }

        $categories = $this->categoryManager->getCategories();

        return $this->render('blog/index.twig', [
            'posts' => $posts ?? null,
            'categories' => $categories ?? null,
            'categoriesofposts' => $categoriesofposts,
        ]);
    }
}
