<?php

namespace App\core;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer extends PHPMailer
{
    /**
     * @param mixed $exceptions
     *
     * @throws Exception
     */
    public function __construct($exceptions, string $to, string $template)
    {
        parent::__construct($exceptions);
        //$this->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->isSMTP();                                            // Send using SMTP
        $this->Host = EMAIL_HOST;                    // Set the SMTP server to send through
        $this->SMTPAuth = false;                                   // Enable SMTP authentication
        // $this->Username = EMAIL_USERNAME;                     // SMTP username
        // $this->Password = EMAIL_PASSWORD;                               // SMTP password
        $this->SMTPSecure = false;
        $this->SMTPAutoTLS = false;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $this->Port = EMAIL_PORT;
        $this->isHTML(true);
        $this->setFrom(EMAIL_USERNAME, 'Romain');
        $this->addAddress($to);
        $this->CharSet = 'UTF-8';
        if ('register' === $template) {
            $this->Subject = 'Inscription au blog de Romain';
        }
        if ('forgot_pwd' === $template) {
            $this->Subject = 'Changement mot de passe';
        }
        if ('update' === $template) {
            $this->Subject = 'Modification de vos informations';
        }
        if ('resetPassword' === $template) {
            $this->Subject = 'Réinitialisation de votre mot de passe';
        }
        if ('contact' === $template) {
            $this->Subject = "Contact d'un utilisateur";
        }
    }

    public static function url(): string
    {
        $https = filter_input(INPUT_SERVER, 'HTTPS');
        $serverName = filter_input(INPUT_SERVER, 'SERVER_NAME');
        $serverPort = filter_input(INPUT_SERVER, 'SERVER_PORT');

        $http = isset($https) && 'on' == $https ? 'https://' : 'http://';

        return $http.$serverName.':'.$serverPort;
    }

    public function send(): bool
    {
        try {
            return parent::send();
        } catch (Exception $e) {
            exit("Le message n'a pas pu être envoyé - Error".$e->getMessage());
        }
    }
}
