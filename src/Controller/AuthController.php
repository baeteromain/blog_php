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

    const TEMPLATE = 'register';

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
        if (!empty($this->post)) {
            $errors = $this->validator->validate($this->post, 'User');

            if (!$errors) {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $this->userManager->createUser($this->post['username'], $this->post['email'], $this->post['password'], self::ROLE['subscriber'], $token);

                $this->session->set('register', 'Merci de valider votre compte via le lien de confirmation envoyé à votre adresse mail '.$this->post['email']);

                $mailer = new Mailer(true, $this->post['email'], self::TEMPLATE);

                $mailer->Body = $this->renderMail('register/mail.html.twig', [
                    'username' => $this->post['username'],
                    'email' => $this->post['email'],
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
            'post' => $this->post ?? null,
        ]);
    }

    public function login()
    {
        if (!empty($this->post)) {
            if (!$this->userManager->login($this->post['username'], $this->post['password'])) {
                $errors['login'] = 'Vos identifiants sont incorrects';
            }

            if (!$errors) {
                header('Location: /');

                exit;
            }
        }

        return $this->render('login/index.twig', [
            'errors' => $errors ?? null,
            'post' => $this->post ?? null,
        ]);
    }
}
