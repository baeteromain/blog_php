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

    const TEMPLATE = [1 => 'register',
        2 => 'forgot_pwd', ];

    /**
     * @var Validation
     */
    private $validator;
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var ErrorController
     */
    private $errorController;


    public function __construct()
    {
        parent::__construct();
        $this->validator = new Validation();
        $this->userManager = new UserManager();
        $this->errorController = new ErrorController();
    }

    public function signin()
    {
        if (!empty($this->post)) {
            $errors = $this->validator->validate($this->post, 'User');

            if (!$errors) {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $this->userManager->createUser($this->post['username'], $this->post['email'], $this->post['password'], self::ROLE['subscriber'], $token);

                $this->session->set('register', 'Merci de valider votre compte via le lien de confirmation envoyé à votre adresse mail '.$this->post['email']);

                $mailer = new Mailer(true, $this->post['email'], self::TEMPLATE[1]);

                $mailer->Body = $this->renderMail('register/mail_confirm_email.html.twig', [
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

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function forgotpwd()
    {
        if (!empty($this->post['email']) && isset($this->post['email'])) {
            $exist = $this->userManager->checkEmail($this->post['email']);
            if ($exist) {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $this->userManager->tokenForgotpwd($this->post['email'], $token);

                $mailer = new Mailer(true, $this->post['email'], self::TEMPLATE[2]);

                $mailer->Body = $this->renderMail('register/mail_forgot_pwd.html.twig', [
                    'email' => $this->post['email'],
                    'url' => Mailer::url(),
                    'token' => $token,
                ]);
                $mailer->send();

                $this->session->set('forgot', 'Votre demande de changement de mot de passe a été envoyé à votre adresse mail');

                header('Location: /forgot_password');

                exit();
            }
            $this->session->set('email_fail', "Aucun compte n'est rattaché à cette adresse mail");
        }

        return $this->render('register/forgotPassword.twig', [
            'errors' => $errors ?? null,
            'post' => $this->post ?? null,
        ]);
    }

    public function changePassword()
    {
        if (!empty($this->post) && isset($this->post['password'], $this->post['confirm_password'])) {
            $postPassword = array_splice($this->post, -2);
            $errors = $this->validator->validate($postPassword, 'User');
            if (!$errors) {
                $this->userManager->updatePassword($postPassword['password'], $this->post['email']);
                $this->userManager->removeToken($this->post['email']);
                if ($this->checkLoggedIn()) {
                    $this->session->stop();
                }

                header('Location: /login');

                exit();
            }
        }

        return $this->render('register/changePassword.twig', [
            'errors' => $errors ?? null,
            'post' => $this->post ?? null,
            'get' => $this->get ?? null,
        ]);
    }

    public function login()
    {
        if (!empty($this->post) && isset($this->post['username'], $this->post['password'])) {
            $user = $this->userManager->login($this->post['username'], $this->post['password']);

            if (!$user) {
                $errors['login'] = 'Vos identifiants sont incorrects';
            }

            if ($user) {
                if ($user->getValid()) {
                    $this->session->set(
                        'user',
                        [
                            'id' => $user->getId(),
                            'username' => $user->getUsername(),
                            'email' => $user->getEmail(),
                            'role' => $user->getRole_id(),
                        ]
                    );
                    header('Location: /');

                    exit;
                }
                $this->session->set('confirm_email_not_valid', "Votre adresse mail n'est pas encore confirmée, merci de consulter votre boite mail");
            }
        }

        if (!empty($this->post) && !isset($this->post['username'], $this->post['password'])) {
            $errors['form_failed_login'] = 'Une erreur est survenue, merci de resaisir vos informations';
        }

        if (!empty($this->get) && isset($this->get['email'], $this->get['token'])) {
            $errors = !$this->userManager->emailConfirmation($this->get['email'], $this->get['token']);
            if ($errors) {
                $this->errorController->errorNotFound();

                exit;
            }

            if (!$errors) {
                $this->userManager->validEmail($this->get['email'], $this->get['token']);
                $this->userManager->removeToken($this->get['email']);

                $this->session->set('confirm_email', 'Votre adresse mail est confirmée, connectez-vous !');
            }
        }

        return $this->render('login/index.twig', [
            'errors' => $errors ?? null,
            'post' => $this->post ?? null,
        ]);
    }

    public function logout()
    {
        if ($this->checkLoggedIn()) {
            $this->session->stop();

            header('Location: /');

            exit;
        }
    }
}
