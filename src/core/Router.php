<?php
namespace App\core;

use AltoRouter;

class Router extends AltoRouter {

    public function __construct()
    {
        $this->map('GET', '/', 'HomeController#Home');     
    }

    public function run()
    {
        $match = $this->match();
        if ($match === false) {
            echo "Erreur 404 ";
        } else {
            list($controller, $action) = explode('#', $match['target']);
            $cname = "\App\Controller\\" . $controller;
            $controllerName = new $cname;
            if (is_callable(array($controllerName, $action))) {
                call_user_func_array(array($controllerName, $action), array($match['params']));
            } else {
                echo 'Error: can not call ' . get_class($controllerName) . '#' . $action;
                // here your routes are wrong.
                // Throw an exception in debug, send a 500 error in production
            }
        }

    }
}