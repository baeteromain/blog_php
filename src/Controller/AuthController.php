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
        if (!empty($this->postAll)) {
            $errors = $this->validator->validate($this->postAll, 'User');

            if (!$errors) {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $this->userManager->createUser($this->postUsername, $this->postEmail, $this->postPassword, self::ROLE['subscriber'], $token);

                $this->session->set('register', 'Merci de valider votre compte via le lien de confirmation envoyé à votre adresse mail '.$this->postEmail);

                $mailer = new Mailer(true, $this->postEmail, self::TEMPLATE[1]);

                $mailer->Body = $this->renderMail('register/mail_confirm_email.html.twig', [
                    'username' => $this->postUsername,
                    'email' => $this->postEmail,
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
            'post' => $this->postAll ?? null,
        ]);
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function forgotpwd()
    {
        if (!empty($this->postEmail) && isset($this->postEmail)) {
            $exist = $this->userManager->checkEmail($this->postEmail);
            if ($exist) {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $this->userManager->tokenForgotpwd($this->postEmail, $token);

                $mailer = new Mailer(true, $this->postEmail, self::TEMPLATE[2]);

                $mailer->Body = $this->renderMail('register/mail_forgot_pwd.html.twig', [
                    'email' => $this->postEmail,
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
            'post' => $this->postAll ?? null,
        ]);
    }

    public function changePassword()
    {
        if (!empty($this->posta) && isset($this->postPassword, $this->postConfirmPassword)) {
            $postPassword = array_splice($this->postAll, -2);
            $errors = $this->validator->validate($postPassword, 'User');
            if (!$errors) {
                $this->userManager->updatePassword($postPassword['password'], $this->postEmail);
                $this->userManager->removeToken($this->postEmail);
                if ($this->checkLoggedIn()) {
                    $this->session->stop();
                }

                header('Location: /login');

                exit();
            }
        }

        return $this->render('register/changePassword.twig', [
            'errors' => $errors ?? null,
            'post' => $this->postAll ?? null,
            'get' => $this->getAll ?? null,
        ]);
    }

    public function login()
    {
        if (!empty($this->postAll) && isset($this->postUsername, $this->postPassword)) {
            $user = $this->userManager->login($this->postUsername, $this->postPassword);

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

        if (!empty($this->postAll) && !isset($this->postUsername, $this->postPassword)) {
            $errors['form_failed_login'] = 'Une erreur est survenue, merci de resaisir vos informations';
        }

        if (!empty($this->getAll) && isset($this->getEmail, $this->getToken)) {
            $errors = !$this->userManager->emailConfirmation($this->getEmail, $this->getToken);
            if ($errors) {
                $this->errorController->errorNotFound();

                exit;
            }

            if (!$errors) {
                $this->userManager->validEmail($this->getEmail, $this->getToken);
                $this->userManager->removeToken($this->getEmail);

                $this->session->set('confirm_email', 'Votre adresse mail est confirmée, connectez-vous !');
            }
        }

        return $this->render('login/index.twig', [
            'errors' => $errors ?? null,
            'post' => $this->postAll ?? null,
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
