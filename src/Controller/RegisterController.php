<?php
namespace App\Controller;

use App\core\Controller;
use App\core\Validation\Validator;
use App\Manager\UserManager;

class RegisterController extends Controller{

    const SUBSCRIBER = 1;
    const ADMIN = 2;

    public function signin()
    {
        $post = $this->post;
        if(!empty($post)){
        
           $validations = new Validator($post);
           $errors = $validations->validate('register');
           if(empty($errors)){
            
               $user = new UserManager;
               $user->createUser($post['username'], $post['email'], $post['password'], self::SUBSCRIBER);
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
