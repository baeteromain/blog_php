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
        if(!empty($_POST)){
        
           $validations = new Validator($_POST);
           $errors = $validations->validate('register');
           if(empty($errors)){
            
               $user = new UserManager;
               $user->createUser($_POST['username'], $_POST['email'], $_POST['password'], self::SUBSCRIBER);
               header('Location: /');
               exit;
           }
           
        }
        return $this->render('register/index.twig', [
            'errors' => $errors ?? null,
            'post' => $_POST ?? null
            ]);
    }
}
