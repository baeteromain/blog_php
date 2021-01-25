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
            $user = $this->userManager->login($this->post['username'], $this->post['password']);

            if (!$user) {
                $errors['login'] = 'Vos identifiants sont incorrects';
            }

            if ($user) {
                if ('1' !== $user->getValid()) {
                    $this->session->set('confirm_email_not_valid', "Votre adresse mail n'est pas encore confirmée, merci de consulter votre boite mail");
                }

                if ('1' === $user->getValid()) {
                    $this->session->set('id', $user->getId())
                        ->set('username', $user->getUsername())
                        ->set('role', $user->getRole_id())
                    ;

                    header('Location: /');

                    exit;
                }
            }
        }

        if (!empty($this->get)) {
            $errors = $this->userManager->emailConfirmation($this->get['token'], $this->get['username']);
            dump($errors);
            if ($errors) {
                $this->session->set('confirm_email_fail', "Le lien d'activation n'est pas valide, ou votre adresse email est déjà vérifiée");
            }

            if (!$errors) {
                $this->userManager->validUser($this->get['username'], $this->get['token']);
                $this->session->set('confirm_email', 'Votre adresse mail est confirmée, connectez-vous !');
            }
        }

        return $this->render('login/index.twig', [
            'errors' => $errors ?? null,
            'post' => $this->post ?? null,
        ]);
    }
}
