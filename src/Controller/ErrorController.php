<?php

namespace App\Controller;

use App\core\Controller;

class ErrorController extends Controller
{
    public function errorNotFound()
    {
        header('Status: 404 Not Found', false, 404);

        return $this->render('error/error_404.html.twig');
    }

    public function errorServer()
    {
        header('Status: 505 Internal server error ', false, 500);

        return $this->render('error/error_500.html.twig');
    }

    public function errorBadRequest()
    {
        header('Status: 400 Bad request ', false, 400);

        return $this->render('error/error_400.html.twig');
    }
}
