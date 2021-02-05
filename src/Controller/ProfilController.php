<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Mailer;
use App\core\Validation\Validation;
use App\Manager\UserManager;

class ProfilController extends Controller
{
    private $userManager;

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

    public function updateProfil()
    {
        $this->checkLoggedIn();
        $user = $this->session->get('user');

        if (!empty($this->post) && isset($this->post['username'], $this->post['email'])) {
            if ($this->post['username'] != $user['username'] || $this->post['email'] != $user['email']) {
                $errors = $this->validator->validate($this->post, 'User');
                if ($user['email'] === $this->post['email']) {
                    unset($errors['email']);
                }

                if ($user['username'] === $this->post['username']) {
                    unset($errors['username']);
                }

                if (!$errors && $user['email'] != $this->post['email']) {
                    $token = bin2hex(openssl_random_pseudo_bytes(16));
                    $this->userManager->updateEmail($user['id'], $this->post['email'], $token);
                    $this->session->remove('user');

                    $mailer = new Mailer(true, $this->post['email'], 'update');

                    $mailer->Body = $this->renderMail('profil/mail_confirm_email.html.twig', [
                        'email' => $this->post['email'],
                        'url' => Mailer::url(),
                        'token' => $token,
                    ]);
                    $mailer->send();

                    $this->session->set('update_email', 'Votre lien d\'activation vous a été envoyé à votre nouvelle adresse');

                    header('Location: /login');

                    exit();
                }

                if (!$errors) {
                    $this->userManager->updateUsername($user['id'], $this->post['username']);
                    $this->session->update('user', 'username', $this->post['username']);

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
