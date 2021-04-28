<?php

namespace App\core;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

abstract class Controller
{
    protected Request $request;
    protected $getAll;
    protected $getId;
    protected $getPage;
    protected $getSlug;
    protected $getRole;
    protected $getEmail;
    protected $getToken;
    protected $getName;
    protected $postAll;
    protected $postId;
    protected $postUsername;
    protected $postPassword;
    protected $postConfirmPassword;
    protected $postEmail;
    protected $postPostComment;
    protected $postPostReply;
    protected $postContent;
    protected $postCommentId;
    protected $postPostId;
    protected $postReply;
    protected $postFirstname;
    protected $postLastname;
    protected $postTitle;
    protected $postSlug;
    protected $postChapo;
    protected $postCategory;
    protected $files;
    protected Session $session;

    public function __construct()
    {
        $this->request = new Request();
        $this->getAll = $this->request->getGetAll();
        $this->getId = $this->request->getGet('id');
        $this->getPage = $this->request->getGet('page');
        $this->getSlug = $this->request->getGet('slug');
        $this->getRole = $this->request->getGet('role');
        $this->getEmail = $this->request->getGet('email');
        $this->getToken = $this->request->getGet('token');
        $this->getName = $this->request->getGet('name');

        $this->postAll = $this->request->getPostAll();
        $this->postId = $this->request->getPost('id');
        $this->postUsername = $this->request->getPost('username');
        $this->postPassword = $this->request->getPost('password');
        $this->postConfirmPassword = $this->request->getPost('confirm_password');
        $this->postEmail = $this->request->getPost('email');
        $this->postPostComment = $this->request->getPost('post_comment');
        $this->postPostReply = $this->request->getPost('post_reply');
        $this->postContent = $this->request->getPost('content');
        $this->postCommentId = $this->request->getPost('comment_id');
        $this->postPostId = $this->request->getPost('post_id');
        $this->postReply = $this->request->getPost('reply');
        $this->postFirstname = $this->request->getPost('firstname');
        $this->postLastname = $this->request->getPost('lastname');
        $this->postTitle = $this->request->getPost('title');
        $this->postSlug = $this->request->getPost('slug');
        $this->postChapo = $this->request->getPost('chapo');
        $this->postCategory = $this->request->getPost('category');

        $this->files = $this->request->getFiles();
        $this->session = $this->request->getSession();

        $this->getTwig();
    }

    public function checkLoggedIn(): bool
    {
        if ($this->session->get('user')) {
            return true;
        }

        header('Location: /login');

        exit;
    }

    public function checkAdmin(): bool
    {
        if ($this->checkLoggedIn()) {
            $user = $this->session->get('user');
            if ('2' === $user['role']) {
                return true;
            }

            $this->session->set('notAdmin', 'Vous ne pouvez pas accéder à cette page sans être administateur du blog');

            header('Location: /profil');

            exit;
        }

        header('Location: /login');

        exit;
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
