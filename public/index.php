<?php

use App\core\Mailer;
use App\core\Router;

require '../vendor/autoload.php';
require '../config/database.php';
require '../config/mailer_config.php';

$router = new Router();

$router->run();

$mail = new Mailer(true, EMAIL_USERNAME);

$mail->Subject ='sujet test';
$mail->Body = "<h1>TEST</h1>";
$mail->send();
