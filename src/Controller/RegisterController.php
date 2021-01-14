<?php
namespace App\Controller;

use App\core\Controller;
use App\core\Mailer;
use App\core\Validation\Validator;
use App\Manager\UserManager;

class RegisterController extends Controller{

    const SUBSCRIBER = 1;
    const ADMIN = 2;

    public function signin()
    {
        $post = $this->post;
        $userManager = new UserManager;
        if(!empty($post)){
        
           $validations = new Validator($post);
           $errors = $validations->validate('register');
           $checkUsername = $userManager->checkUsername($post['username']);
           $checkEmail = $userManager->checkEmail($post['email']);
           if($checkUsername){
               $errors['username'] = $checkUsername;
           }
           if($checkEmail){
               $errors['email'] = $checkEmail;
           }
           if(empty($errors)){
            
               
               $userManager->createUser($post['username'], $post['email'], $post['password'], self::SUBSCRIBER);
               
               $mailer = new Mailer(true, $post['email']);

               $mailer->Subject = 'Inscription au blog de Romain';
               $mailer->Body = '<h1>Merci pour votre inscription</h1>';
               $mailer->send();
               
               header('Location: /');
               exit;
           }
           
        }
        return $this->render('register/index.twig', [
            'errors' => $errors ?? null,
            'post' => $post ?? null
            ]);
    }
}
