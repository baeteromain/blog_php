<?php

namespace App\Controller;

use App\core\Controller;
use App\Manager\CategoryManager;
use App\Manager\PostManager;
use App\Model\Pagination;

class BlogController extends Controller
{
    private $postManager;
    private $categoryManager;
    private $pagination;

    public function __construct()
    {
        parent::__construct();

        $this->postManager = new PostManager();
        $this->categoryManager = new CategoryManager();
        $this->pagination = new Pagination();
    }

    public function listPosts()
    {
        if (empty($this->get['page'])) {
            $this->get['page'] = 1;
        }
        $pagination = $this->pagination->paginate(5, $this->get['page'], $this->postManager->total());
        $posts = $this->postManager->getPosts($pagination->getLimit(), $this->pagination->getStart());

        foreach ($posts as $post) {
            $categoriesofposts[$post->getId()] = $this->postManager->getCategoryByPost($post->getId());
        }

        $categories = $this->categoryManager->getCategories();

        return $this->render('blog/index.twig', [
            'posts' => $posts ?? null,
            'categories' => $categories ?? null,
            'categoriesofposts' => $categoriesofposts,
            'pagination' => $pagination,
        ]);
    }

    public function singlePost()
    {
        if (!empty($this->get['id'])) {
            $post = $this->postManager->getPostById($this->get['id']);
            $categoriesOfPost = $this->postManager->getCategoryByPost($this->get['id']);
        }

        return $this->render('blog/single/index.twig', [
            'post' => $post ?? null,
            'categoriesOfPost' => $categoriesOfPost ?? null,
            // 'categoriesofposts' => $categoriesofposts,
            // 'pagination' => $pagination,
        ]);
    }
}
