<?php

namespace App\Controller;

use App\core\Controller;

class HomeController extends Controller
{
    public function Home()
    {
        return $this->render('home/index.twig');
    }
}
