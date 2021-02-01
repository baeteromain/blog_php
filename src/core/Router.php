<?php

namespace App\core;

use AltoRouter;

class Router extends AltoRouter
{
    public function __construct()
    {
        $this->map('GET', '/', 'HomeController#Home');
        $this->map('GET', '/register', 'AuthController#signin');
        $this->map('POST', '/register', 'AuthController#signin');
        $this->map('GET', '/login', 'AuthController#login');
        $this->map('POST', '/login', 'AuthController#login');
        $this->map('GET', '/forgot_password', 'AuthController#forgotpwd');
        $this->map('POST', '/forgot_password', 'AuthController#forgotpwd');
        $this->map('GET', '/changePassword', 'AuthController#changePassword');
        $this->map('POST', '/changePassword', 'AuthController#changePassword');
    }

    public function run()
    {
        $match = $this->match();
        if (false === $match) {
            echo 'Erreur 404 ';
        } else {
            list($controller, $action) = explode('#', $match['target']);
            $cname = '\\App\\Controller\\'.$controller;
            $controllerName = new $cname();
            if (is_callable([$controllerName, $action])) {
                call_user_func_array([$controllerName, $action], [$match['params']]);
            } else {
                echo 'Error: can not call '.get_class($controllerName).'#'.$action;
                // here your routes are wrong.
                // Throw an exception in debug, send a 500 error in production
            }
        }
    }
}
