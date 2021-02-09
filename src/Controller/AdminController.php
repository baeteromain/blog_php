<?php

namespace App\Controller;

use App\core\Controller;
use App\Manager\UserManager;

class AdminController extends Controller
{
    private $userManager;

    public function __construct()
    {
        parent::__construct();

        $this->userManager = new UserManager();
    }

    public function index()
    {
        $this->checkAdmin();

        return $this->render('admin/index.twig');
    }

    public function adminUsers()
    {
        $this->checkAdmin();

        $users = $this->userManager->getUsers();

        return $this->render('admin/admin_users/index.twig', [
            'users' => $users,
        ]);
    }

    public function upgradeUser()
    {
        $this->checkAdmin();

        $user = $this->userManager->getUserById($this->get['id']);

        if (!empty($this->get) && isset($this->get['id']) && $user) {
            if ('1' === $user->getRole_id()) {
                $this->userManager->upgradeRole($user->getId(), '2');
            }

            if ('2' === $user->getRole_id()) {
                $this->userManager->upgradeRole($user->getId(), '1');
            }

            $this->session->set('upgrade_user', 'Le role de l\'utilisateur à bien été changé');

            header('Location: /admin/users');

            exit();
        }

        $this->session->set('error_upgrade_user', 'Un problème est survenu lors du changement du role de l\'utilisateur');

        $users = $this->userManager->getUsers();

        return $this->render('admin/admin_users/index.twig', [
            'users' => $users,
        ]);
    }

    public function deleteUsers()
    {
        $this->checkAdmin();

        $user = $this->userManager->getUserById($this->get['id']);

        if (!empty($this->get) && isset($this->get['id']) && $user) {
            $this->userManager->deleteUser($this->get['id']);
            $this->session->set('delete_user', 'L\'utilisateur à bien été supprimé');

            header('Location: /admin/users');

            exit();
        }

        $this->session->set('error_delete_user', 'Un problème est survenu lors de la suppression de l\'utilisateur');

        $users = $this->userManager->getUsers();

        return $this->render('admin/admin_users/index.twig', [
            'users' => $users,
        ]);
    }
}
