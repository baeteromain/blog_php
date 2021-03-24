<?php

namespace App\Controller;

use App\core\Controller;
use App\Manager\CommentManager;

class CommentController extends Controller
{
    const PUBLISH = 1;
    const UNPUBLISH = 0;

    private $commentManager;

    public function __construct()
    {
        parent::__construct();

        $this->commentManager = new CommentManager();
    }

    public function index()
    {
        $this->checkAdmin();

        $comments = $this->commentManager->getComments();

        if (!empty($this->get['publish']) && 'yes' === $this->get['publish']) {
            $comments = $this->commentManager->getCommentsFilter(self::PUBLISH);
        }

        if (!empty($this->get['publish']) && 'no' === $this->get['publish']) {
            $comments = $this->commentManager->getCommentsFilter(self::UNPUBLISH);
        }

        return $this->render('admin/admin_comments/index.twig', [
            'comments' => $comments,
        ]);
    }

    public function validateComment()
    {
        $this->checkAdmin();

        $comments = $this->commentManager->getComments();

        if (!empty($this->get['id'])) {
            $this->commentManager->validateComment($this->get['id']);

            $this->session->set('validate_comment', 'Le commentaire a bien été validé');

            header('Location: /admin/comments');

            exit();
        }

        return $this->render('admin/admin_comments/index.twig', [
            'comments' => $comments,
        ]);
    }
}
