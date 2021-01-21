<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Mailer;
use App\core\Validation\Validation;
use App\Manager\UserManager;

class AuthController extends Controller
{
    const ROLE = [
        'subscriber' => 1,
        'admin' => 2,
    ];
    // const SUBSCRIBER = 1;
    // const ADMIN = 2;
    // const REGISTER_TEMPLATE = 'register';

    const TEMPLATE = ['register'];

    private $validator;
    private $userManager;

    public function __construct()
    {
        parent::__construct();
        $this->validator = new Validation();
        $this->userManager = new UserManager();
    }

    public function signin()
    {
        $post = $this->post;

        if (!empty($post)) {
            $errors = $this->validator->validate($post, 'User');

            if (!$errors) {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $this->userManager->createUser($post['username'], $post['email'], $post['password'], self::ROLE['subscriber'], $token);

                $mailer = new Mailer(true, $post['email'], self::TEMPLATE[1]);

                $mailer->Body = $this->renderMail('register/mail.html.twig', [
                    'username' => $post['username'],
                    'email' => $post['email'],
                    'url' => Mailer::url(),
                    'token' => $token,
                ]);
                $mailer->send();

                header('Location: /login');

                exit();
            }
        }

        return $this->render('register/index.twig', [
            'errors' => $errors ?? null,
            'post' => $post ?? null,
        ]);
    }

    public function login()
    {
        $post = $this->post;
        if (!empty($post)) {
            if (!$this->userManager->login($post['username'], $post['password'])) {
                $errors['login'] = 'Vos identifiants sont incorrects';
            }

            if (!$errors) {
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
