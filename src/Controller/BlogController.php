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


    /**
     * @var PostManager
     */
    private $postManager;
    /**
     * @var CategoryManager
     */
    private $categoryManager;
    /**
     * @var Pagination
     */
    private $pagination;
    /**
     * @var CommentManager
     */
    private $commentManager;
    /**
     * @var Validation
     */
    private $validator;

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
        if (!isset($this->get['id'])) {
            header('Location: /articles');

            exit();
        }

        $post = $this->postManager->getPostById($this->get['id']);

        if (empty($post)) {
            header('Location: /articles');

            exit();
        }

        $user = $this->session->get('user');

        if (isset($this->post['post_comment'])) {
            unset($this->post['post_comment']);

            $errors = $this->validator->validate($this->post, 'Comment');

            if (!$errors) {
                $this->addComment($user['id'], $this->post['content'], $this->post['post_id']);

                header('Location: /articles/'.$post->getSlug().'-'.$post->getId().'?id='.$post->getId());

                exit();
            }
        }

        if (isset($this->post['post_reply'])) {
            unset($this->post['post_reply']);

            $errors_reply = $this->validator->validate($this->post, 'Comment');
            if (!$errors_reply) {
                $this->addCommentReply($user['id'], $this->post['comment_id'], $this->post['reply'], $this->post['post_id']);

                header('Location: /articles/'.$post->getSlug().'-'.$post->getId().'?id='.$post->getId());

                exit();
            }
        }

        $comments = $this->commentManager->getCommentsByPost($this->get['id']);

        $categoriesOfPost = $this->postManager->getCategoryByPost($this->get['id']);

        $replyComment = [];

        foreach ($comments as $comment) {
            $replyComment[$comment->getId()] = $this->commentManager->getReplyByComment($comment->getId());
        }

        return $this->render('blog/single/index.twig', [
            'content' => $this->post['content'] ?? null,
            'reply' => $this->post['reply'] ?? null,
            'replyComment' => $replyComment ?? null,
            'errors_reply' => $errors_reply ?? null,
            'errors' => $errors ?? null,
            'comments' => $comments ?? null,
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
            $pagination = $this->pagination->paginate(2, $this->get['page'], $this->postManager->totalByCategory($this->get['id']));

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

    private function addCommentReply($user, $comment_id, $content, $post_id)
    {
        $this->commentManager->addComment($content, $comment_id, $post_id, $user);
    }
}
