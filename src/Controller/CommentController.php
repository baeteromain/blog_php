<?php

namespace App\Controller;

use App\core\Controller;
use App\Manager\CommentManager;

class CommentController extends Controller
{
    const PUBLISH = 1;
    const UNPUBLISH = 0;

    /**
     * @var CommentManager
     */
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

        if (isset($this->get['publish'])) {
            $comments = $this->commentManager->getCommentsFilter(self::PUBLISH);
        }

        if (isset($this->get['unpublish'])) {
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

    public function deleteComment()
    {
        $this->checkAdmin();

        if (!empty($this->get['id'])) {
            $replies = $this->commentManager->getReplyByComment($this->get['id']);

            if ($replies) {
                foreach ($replies as $reply) {
                    $this->commentManager->deleteComment($reply->getID());
                }
            }

            $this->commentManager->deleteComment($this->get['id']);

            $this->session->set('delete_comment', 'Le commentaire a bien été supprimé');

            header('Location: /admin/comments');

            exit();
        }
        $this->session->set('error_delete_comment', 'Un problème est survenu lors de la suppression du commentaire');

        $comments = $this->commentManager->getComments();

        return $this->render('admin/admin_comments/index.twig', [
            'comments' => $comments,
        ]);
    }
}
