<?php

namespace App\Controller;

use App\core\Controller;
use App\Manager\UserManager;

class ProfilController extends Controller
{
    private $userManager;

    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
    }

    public function profil()
    {
        $this->checkLoggedIn();

        return $this->render('profil/index.twig');
    }

    public function updateProfil()
    {
        $this->checkLoggedIn();
        $user = $this->session->get('user');
        if (!empty($this->post) && isset($this->post['username'])) {
            $this->userManager->updateUser($user['id'], $this->post['username'], $this->post['email']);
        }

        return $this->render('profil/update.twig');
    }
}
