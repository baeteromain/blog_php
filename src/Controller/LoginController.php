<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Validation\Validator;
use App\Manager\UserManager;

class LoginController extends Controller
{
    public function login()
    {
        $post = $this->post;
        $userManager = new UserManager();
        if (!empty($post)) {
            $validations = new Validator($post);
            $errors = $validations->validate('login');
            if (empty($errors)) {
                $result = $userManager->login($post['username'], $post['password']);
                if (true !== $result) {
                    $errors['login'] = $result;
                }
            }

            if (empty($errors)) {
                header('Location: /');
                exit;
            }
        }

        return $this->render('login/index.twig', [
            'errors' => $errors ?? null,
            'post' => $post ?? null,
        ]);
    }
}
