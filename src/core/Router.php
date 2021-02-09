<?php

namespace App\core;

use AltoRouter;
use App\Controller\ErrorController;
use Exception;

class Router extends AltoRouter
{
    public function __construct()
    {
        $this->errorController = new ErrorController();

        $this->map('GET', '/', 'HomeController#Home');
        $this->map('GET', '/register', 'AuthController#signin');
        $this->map('POST', '/register', 'AuthController#signin');
        $this->map('GET', '/login', 'AuthController#login');
        $this->map('POST', '/login', 'AuthController#login');
        $this->map('GET', '/logout', 'AuthController#logout');
        $this->map('GET', '/forgot_password', 'AuthController#forgotpwd');
        $this->map('POST', '/forgot_password', 'AuthController#forgotpwd');
        $this->map('GET', '/changePassword', 'AuthController#changePassword');
        $this->map('POST', '/changePassword', 'AuthController#changePassword');
        $this->map('GET', '/profil', 'ProfilController#profil');
        $this->map('POST', '/profil/update', 'ProfilController#updateProfil');
        $this->map('GET', '/profil/update', 'ProfilController#updateProfil');
        $this->map('GET', '/admin', 'AdminController#index');
        $this->map('GET', '/admin/users', 'AdminController#adminUsers');
        $this->map('GET', '/admin/users/upgrade', 'AdminController#upgradeUser');
        $this->map('GET', '/admin/users/delete', 'AdminController#deleteUsers');
    }

    public function run()
    {
        $match = $this->match();
        if (false === $match) {
            $this->errorController->errorNotFound();
        } else {
            list($controller, $action) = explode('#', $match['target']);
            $cname = '\\App\\Controller\\'.$controller;
            $controllerName = new $cname();
            if (is_callable([$controllerName, $action])) {
                call_user_func_array([$controllerName, $action], [$match['params']]);
            } else {
                $this->errorController->errorNotFound();

                throw new Exception('Error: can not call '.get_class($controllerName).'#'.$action);
            }
        }
    }
}
