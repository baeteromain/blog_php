<?php

namespace App\Controller;

use App\core\Controller;
use App\core\Mailer;
use App\core\Validation\Validation;

class ContactController extends Controller
{
    const TEMPLATE_CONTACT = 'contact';

    /**
     * @var Validation
     */
    private $validator;

    public function __construct()
    {
        parent::__construct();

        $this->validator = new Validation();
    }

    /**
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function index()
    {
        if (!empty($this->postAll)) {
            $errors = $this->validator->validate($this->postAll, 'Contact');

            if (!$errors) {
                $mailer = new Mailer(true, EMAIL_USERNAME, self::TEMPLATE_CONTACT);

                $mailer->Body = $this->renderMail('contact/mail_contact.html.twig', [
                    'firstname' => $this->postFirstname,
                    'lastname' => $this->postLastname,
                    'email' => $this->postEmail,
                    'content' => $this->postContent,
                ]);
                $mailer->send();

                $this->session->set('contact', 'Votre message à bien été envoyé !');

                header('Location: /contact');

                exit();
            }
        }

        return $this->render('contact/index.twig', [
            'errors' => $errors ?? null,
            'post' => $this->postAll ?? null,
        ]);
    }
}
