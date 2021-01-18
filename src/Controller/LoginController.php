<?php 
namespace App\Controller;

use App\core\Controller;

class LoginController extends Controller{

    public function login()
    {
        return $this->render('login/index.twig');
    }

}