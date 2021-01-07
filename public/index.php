<?php

use App\core\Mailer;
use App\core\Router;

require '../vendor/autoload.php';
require '../config/database.php';
require '../config/mailer_config.php';

$router = new Router();

$router->run();

