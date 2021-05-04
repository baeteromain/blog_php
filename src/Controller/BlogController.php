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
        if (empty($this->getPage)) {
            $this->getPage = 1;
        }
        $pagination = $this->pagination->paginate(5, $this->getPage, $this->postManager->total());
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
        if (!isset($this->getId)) {
            header('Location: /articles');

            exit();
        }

        $post = $this->postManager->getPostById($this->getId);

        if (empty($post)) {
            header('Location: /articles');

            exit();
        }

        $user = $this->session->get('user');

        if (isset($this->postPostComment)) {
            unset($this->postPostComment);

            $errors = $this->validator->validate($this->postAll, 'Comment');

            if (!$errors) {
                $this->addComment($user['id'], $this->postContent, $this->postPostId);

                $this->session->set('post_comment', 'Votre commentaire a bien été posté, il est en attente de validation.');

                header('Location: /articles/'.$post->getSlug().'-'.$post->getId().'?id='.$post->getId().'#comment');

                exit();
            }
        }

        if (isset($this->postPostReply)) {
            unset($this->postPostReply);

            $errors_reply = $this->validator->validate($this->post, 'Comment');
            if (!$errors_reply) {
                $this->addCommentReply($user['id'], $this->postCommentId, $this->postReply, $this->postPostId);

                $this->session->set('reply_comment', 'Votre réponse a bien été posté, elle est en attente de validation.');

                header('Location: /articles/'.$post->getSlug().'-'.$post->getId().'?id='.$post->getId().'#comment');

                exit();
            }
        }

        $comments = $this->commentManager->getCommentsByPost($this->getId);

        $categoriesOfPost = $this->postManager->getCategoryByPost($this->getId);

        $replyComment = [];

        foreach ($comments as $comment) {
            $replyComment[$comment->getId()] = $this->commentManager->getReplyByComment($comment->getId());
        }

        return $this->render('blog/single/index.twig', [
            'content' => $this->postContent ?? null,
            'reply' => $this->postReply ?? null,
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
        if (empty($this->getPage)) {
            $this->getPage = 1;
        }

        if (isset($this->getId)) {
            $pagination = $this->pagination->paginate(2, $this->getPage, $this->postManager->totalByCategory($this->getId));

            $posts = $this->postManager->getPostByCategories($pagination->getLimit(), $this->pagination->getStart(), $this->getId);
        }

        foreach ($posts as $post) {
            $categoriesofposts[$post->getId()] = $this->postManager->getCategoryByPost($post->getId());
        }

        $categories = $this->categoryManager->getCategories();

        $cat = $this->categoryManager->getCategoryById($this->getId);

        return $this->render('blog/postsByCategories.twig', [
            'posts' => $posts ?? null,
            'pagination' => $pagination,
            'categories' => $categories ?? null,
            'categoriesofposts' => $categoriesofposts ?? null,
            'categories_id' => $this->getId ?? null,
            'categories_slug' => $this->getSlug ?? null,
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
