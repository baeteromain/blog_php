<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Mailer;
use App\core\Validation\Validator;
use App\Manager\UserManager;

class RegisterController extends Controller
{
    const SUBSCRIBER = 1;
    const ADMIN = 2;
    const REGISTER_TEMPLATE = 'register';

    public function signin()
    {
        $post = $this->post;
        $userManager = new UserManager();
        if (!empty($post)) {
            $validations = new Validator($post);
            $errors = $validations->validate('register');
            $checkUsername = $userManager->checkUsername($post['username']);
            $checkEmail = $userManager->checkEmail($post['email']);
            if ($checkUsername) {
                $errors['username'] = $checkUsername;
            }
            if ($checkEmail) {
                $errors['email'] = $checkEmail;
            }

            if (empty($errors)) {
                $token = bin2hex(openssl_random_pseudo_bytes(16));
                $userManager->createUser($post['username'], $post['email'], $post['password'], self::SUBSCRIBER, $token);

                $mailer = new Mailer(true, $post['email'], self::REGISTER_TEMPLATE);

                $http = isset($_SERVER['HTTPS']) && 'on' == $_SERVER['HTTPS'] ? 'https://' : 'http://';
                $url = $http.$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'];

                $mailer->Body = $this->renderMail('register/test.html.twig', [
                    'username' => $post['username'],
                    'email' => $post['email'],
                    'url' => $url,
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
}
