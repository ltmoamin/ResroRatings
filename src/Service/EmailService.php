<?php

namespace App\Service;

use App\Entity\Reponse;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmailOnHundredAvis($emailAddresses)
    {
        $email = (new Email())
            ->from('aminfsm2001@gmail.com')
            ->to(...$emailAddresses)
            ->subject('Gestion Mohamed Amin')
            ->text('Hala Madrid')
            ->html('<h1 style="color: #fff300; background-color: #0073ff; width: 500px; padding: 16px 0; text-align: center; border-radius: 50px;">Tu as plus de 100 avis</h1>');

        $this->mailer->send($email);
        return new Reponse('Email sent');
    }
}
