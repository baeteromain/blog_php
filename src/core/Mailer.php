<?php

namespace App\core;

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

class Mailer extends PHPMailer
{
    public function __construct($exceptions, string $to, string $username, string $template)
    {
        parent::__construct($exceptions);
        //$this->SMTPDebug = SMTP::DEBUG_SERVER;
        $this->isSMTP();                                            // Send using SMTP
        $this->Host = EMAIL_HOST;                    // Set the SMTP server to send through
        $this->SMTPAuth = true;                                   // Enable SMTP authentication
        $this->Username = EMAIL_USERNAME;                     // SMTP username
        $this->Password = EMAIL_PASSWORD;                               // SMTP password
        $this->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $this->Port = EMAIL_PORT;
        $this->isHTML(true);
        $this->setFrom(EMAIL_USERNAME, 'Romain');
        $this->addAddress($to);
        if ('register' === $template) {
            $this->Subject = 'Inscription au blog de Romain';
            $message = file_get_contents('../templates/register/mail.html');
            $message = str_replace('%username%', $username, $message);
            $message = str_replace('%email%', $to, $message);
            $this->Body = $message;
        } 
    }

    public function send()
    {
        try {
            $r = parent::send();
            echo 'Message a été envoyé';

            return $r;
        } catch (Exception $e) {
            echo "Le message n'a pas pu être envoyé - Error: {$e->getMessage()}";
        }
    }
}
