<?php

use App\core\Router;

require '../vendor/autoload.php';
require '../config/database.php';

$router = new Router();

$router->run();

