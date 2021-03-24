<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Validation\Validation;
use App\Manager\CategoryManager;
use App\Manager\CommentManager;
use App\Manager\PostManager;
use App\Model\Pagination;

class BlogController extends Controller
{
    private $postManager;
    private $categoryManager;
    private $pagination;
    private $commentManager;

    public function __construct()
    {
        parent::__construct();

        $this->postManager = new PostManager();
        $this->categoryManager = new CategoryManager();
        $this->pagination = new Pagination();
        $this->commentManager = new CommentManager();
        $this->validator = new Validation();
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
            'categoriesofposts' => $categoriesofposts ?? null,
            'pagination' => $pagination,
        ]);
    }

    public function singlePost()
    {
        if (!empty($this->get['id'])) {
            $user = $this->session->get('user');

            $post = $this->postManager->getPostById($this->get['id']);
            $categoriesOfPost = $this->postManager->getCategoryByPost($this->get['id']);
        }

        if (!empty($this->post)) {
            $errors = $this->validator->validate($this->post, 'Comment');
            if (!$errors) {
                $this->addComment($user['id'], $this->post['content'], $this->post['post_id']);
            }
        }

        return $this->render('blog/single/index.twig', [
            'errors' => $errors ?? null,
            'user' => $user ?? null,
            'post' => $post ?? null,
            'categoriesOfPost' => $categoriesOfPost ?? null,
        ]);
    }

    public function listPostsByCategories()
    {
        if (empty($this->get['page'])) {
            $this->get['page'] = 1;
        }

        if (!empty($this->get['id'])) {
            $pagination = $this->pagination->paginate(2, $this->get['page'], $this->postManager->totatByCategory($this->get['id']));

            $posts = $this->postManager->getPostByCategories($pagination->getLimit(), $this->pagination->getStart(), $this->get['id']);
        }

        foreach ($posts as $post) {
            $categoriesofposts[$post->getId()] = $this->postManager->getCategoryByPost($post->getId());
        }

        $categories = $this->categoryManager->getCategories();

        $cat = $this->categoryManager->getCategoryById($this->get['id']);

        return $this->render('blog/postsByCategories.twig', [
            'posts' => $posts ?? null,
            'pagination' => $pagination,
            'categories' => $categories ?? null,
            'categoriesofposts' => $categoriesofposts ?? null,
            'categories_id' => $this->get['id'] ?? null,
            'categories_slug' => $this->get['slug'] ?? null,
            'category_name' => $cat->getName(),
        ]);
    }

    private function addComment($user, $content, $post_id)
    {
        $this->commentManager->addComment($content, null, $post_id, $user);
    }
}
