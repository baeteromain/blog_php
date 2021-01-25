<?php

use App\core\Router;

require '../vendor/autoload.php';

require '../config/database.php';

require '../config/mailer_config.php';

session_start();

$router = new Router();

$router->run();
