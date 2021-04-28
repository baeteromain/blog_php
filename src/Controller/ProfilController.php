<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Mailer;
use App\core\Validation\Validation;
use App\Manager\UserManager;

class ProfilController extends Controller
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var Validation
     */
    private $validator;

    public function __construct()
    {
        parent::__construct();
        $this->userManager = new UserManager();
        $this->validator = new Validation();
    }

    public function profil()
    {
        $this->checkLoggedIn();

        return $this->render('profil/index.twig');
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function updateProfil()
    {
        $this->checkLoggedIn();
        $user = $this->session->get('user');
        if (!empty($this->postAll) && isset($this->postUsername, $this->postEmail)) {
            if ($this->postUsername != $user['username'] || $this->postEmail != $user['email']) {
                $errors = $this->validator->validate($this->post, 'User');
                if ($user['email'] === $this->postEmail) {
                    unset($errors['email']);
                }

                if ($user['username'] === $this->postUsername) {
                    unset($errors['username']);
                }

                if (!$errors) {
                    if ($user['email'] != $this->postEmail) {
                        $token = bin2hex(openssl_random_pseudo_bytes(16));
                        $this->userManager->updateUser($user['id'], $user['username'], $this->postEmail, $token, '0');
                        $this->session->remove('user');

                        $mailer = new Mailer(true, $this->postEmail, 'update');

                        $mailer->Body = $this->renderMail('profil/mail_confirm_email.html.twig', [
                            'email' => $this->postEmail,
                            'url' => Mailer::url(),
                            'token' => $token,
                        ]);
                        $mailer->send();

                        $this->session->set('update_email', "Votre lien d'activation vous a été envoyé à votre nouvelle adresse");

                        header('Location: /login');

                        exit();
                    }

                    $this->userManager->updateUser($user['id'], $this->postUsername, $user['email']);
                    $this->session->update('user', 'username', $this->postUsername);

                    header('Location: /profil');

                    exit();
                }
            }
        }

        return $this->render(
            'profil/update.twig',
            [
                'errors' => $errors ?? null,
            ]
        );
    }
}
