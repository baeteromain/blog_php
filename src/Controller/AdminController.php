<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Mailer;
use App\core\Validation\Validation;
use App\Manager\CommentManager;
use App\Manager\UserManager;
use App\Model\Pagination;

class AdminController extends Controller
{
    const ROLE = [
        'subscriber' => 1,
        'admin' => 2,
    ];

    const FILTER = [
        'publish' => 1,
        'not_publish' => 0,
    ];
    /**
     * @var Pagination
     */
    private $pagination;
    /**
     * @var Validation
     */
    private $validator;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var CommentManager
     */
    private $commentManager;

    public function __construct()
    {
        parent::__construct();

        $this->userManager = new UserManager();
        $this->commentManager = new CommentManager();
        $this->validator = new Validation();
        $this->pagination = new Pagination();
    }

    public function index()
    {
        $this->checkAdmin();

        $countCommentsUnPublish = $this->commentManager->totalComments(self::FILTER['not_publish']);

        $countReplyUnPublish = $this->commentManager->totalReplyComments(self::FILTER['not_publish']);

        $pagination = $this->pagination->paginate(5, null, $this->commentManager->totalComments(self::FILTER['not_publish']));
        $commentsUnPublish = $this->commentManager->getCommentsFilter(self::FILTER['not_publish'], $pagination->getLimit(), $this->pagination->getStart());

        return $this->render('admin/index.twig', [
            'commentsUnPublish' => $commentsUnPublish ?? null,
            'countCommentsUnPublish' => $countCommentsUnPublish ?? null,
            'countReplyUnPublish' => $countReplyUnPublish ?? null,
        ]);
    }

    public function adminUsers()
    {
        $this->checkAdmin();

        $users = $this->userManager->getUsers();

        return $this->render('admin/admin_users/index.twig', [
            'users' => $users,
        ]);
    }

    public function updateUserRole()
    {
        $this->checkAdmin();

        $user = $this->userManager->getUserById($this->getId);

        if (!empty($this->getAll) && isset($this->getId, $this->getRole) && $user) {
            if ('subscriber' === $this->getRole || 'admin' === $this->getRole) {
                if ('admin' === $this->getRole) {
                    $this->userManager->updateRole($user->getId(), self::ROLE['admin']);
                }
                if ('subscriber' === $this->getRole) {
                    $this->userManager->updateRole($user->getId(), self::ROLE['subscriber']);
                }

                $this->session->set('upgrade_user', "Le role de l'utilisateur à bien été changé");

                header('Location: /admin/users');

                exit();
            }
        }

        $this->session->set('error_upgrade_user', "Un problème est survenu lors du changement du role de l'utilisateur");

        $users = $this->userManager->getUsers();

        return $this->render('admin/admin_users/index.twig', [
            'users' => $users,
        ]);
    }

    public function reset()
    {
        $this->checkAdmin();

        $user = $this->userManager->getUserById($this->getId);

        if (!empty($this->getAll) & isset($this->getEmail)) {
            $errors = $this->validator->validate($this->getAll, 'User');
            if (!$errors['email']) {
                $this->resetEmail($user->getId(), $user->getUsername());
            }
        }

        return $this->render('admin/admin_users/reset.twig', [
            'user' => $user,
            'errors' => $errors ?? null,
        ]);
    }

    public function resetEmail($id, $username)
    {
        $token = bin2hex(openssl_random_pseudo_bytes(16));

        $this->userManager->updateUser($id, $username, $this->getEmail, $token, '0');

        $mailer = new Mailer(true, $this->getEmail, 'update');

        $mailer->Body = $this->renderMail('profil/mail_confirm_email.html.twig', [
            'email' => $this->getEmail,
            'url' => Mailer::url(),
            'token' => $token,
        ]);
        $mailer->send();

        header('Location: /admin/users');

        exit();
    }

    public function resetPassword()
    {
        $this->checkAdmin();

        $password = bin2hex(openssl_random_pseudo_bytes(8));
        $user = $this->userManager->getUserById($this->getId);

        $this->userManager->updatePassword($password, $user->getEmail());

        $mailer = new Mailer(true, $user->getEmail(), 'resetPassword');

        $mailer->Body = $this->renderMail('admin/admin_users/mail_reset_password.html.twig', [
            'email' => $user->getEmail(),
            'url' => Mailer::url(),
            'password' => $password,
        ]);
        $mailer->send();

        header('Location: /admin/users');

        exit();
    }

    public function deleteUsers()
    {
        $this->checkAdmin();

        $user = $this->userManager->getUserById($this->getId);

        if (!empty($this->getAll) && isset($this->getId) && $user) {
            $this->userManager->deleteUser($this->getId);
            $this->session->set('delete_user', "L'utilisateur à bien été supprimé");

            header('Location: /admin/users');

            exit();
        }

        $this->session->set('error_delete_user', "Un problème est survenu lors de la suppression de l'utilisateur");

        $users = $this->userManager->getUsers();

        return $this->render('admin/admin_users/index.twig', [
            'users' => $users,
        ]);
    }
}
