<?php
namespace App\Controller;

use App\core\Controller;

class RegisterController extends Controller{

    public function Register()
    {
        return $this->render('register/index.twig');
    }
}