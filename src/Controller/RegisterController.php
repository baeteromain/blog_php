<?php
namespace App\Controller;

use App\core\Controller;
use App\core\Validation\Validator;

class RegisterController extends Controller{

    public function Register()
    {
        return $this->render('register/index.twig');
    }

    public function Signin()
    {
        if(isset($_POST)){
           $validations = new Validator($_POST);
           $errors = $validations->validate('register');
           return $this->render('register/index.twig', [
            'errors' => $errors,
            'post' => $_POST
            ]);
        }
        return $this->render('register/index.twig');
    }
}
