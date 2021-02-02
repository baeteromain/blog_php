<?php

namespace App\Controller;

use App\core\Controller;

class ProfilController extends Controller
{
    public function profil()
    {
        if (!$this->checkLoggedIn()) {
            header('Location: /login');

            exit;
        }

        return $this->render('profil/index.twig', [
            'errors' => $errors ?? null,
            'post' => $this->post ?? null,
        ]);
    }
}
