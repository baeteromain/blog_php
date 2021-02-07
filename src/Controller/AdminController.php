<?php

namespace App\Controller;

use App\core\Controller;

class AdminController extends Controller
{
    public function index()
    {
        return $this->render('admin/index.twig');
    }
}
