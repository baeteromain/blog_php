<?php

namespace App\Controller;

use App\core\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $this->checkAdmin();

        return $this->render('admin/index.twig');
    }
}
