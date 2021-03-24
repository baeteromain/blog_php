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
        $this->map('GET', '/admin/users/update', 'AdminController#updateUserRole');
        $this->map('GET', '/admin/users/delete', 'AdminController#deleteUsers');
        $this->map('GET', '/admin/users/reset', 'AdminController#reset');
        $this->map('POST', '/admin/users/reset', 'AdminController#reset');
        $this->map('GET', '/admin/users/reset/password', 'AdminController#resetPassword');
        $this->map('GET', '/admin/categories', 'CategoryController#index');
        $this->map('GET', '/admin/categories/add', 'CategoryController#addCategory');
        $this->map('GET', '/admin/categories/update', 'CategoryController#updateCategory');
        $this->map('GET', '/admin/categories/delete', 'CategoryController#deleteCategory');
        $this->map('GET', '/admin/posts', 'PostController#index');
        $this->map('GET', '/admin/posts/add', 'PostController#addPost');
        $this->map('POST', '/admin/posts/add', 'PostController#addPost');
        $this->map('GET', '/admin/posts/update', 'PostController#updatePost');
        $this->map('POST', '/admin/posts/update', 'PostController#updatePost');
        $this->map('GET', '/admin/posts/delete', 'PostController#deletePost');
        $this->map('GET', '/articles', 'BlogController#listPosts');
        $this->map('GET', '/articles/[*:slug]-[i:id]', 'BlogController#singlePost');
        $this->map('GET', '/categories/[*:slug]', 'BlogController#listPostsByCategories');
        $this->map('POST', '/comments/add', 'BlogController#singlePost');
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
