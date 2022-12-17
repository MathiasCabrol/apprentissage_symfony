<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{

    private $replyTo;
    private $appName;

    public function __construct(private MailerInterface $mailer, $replyTo, $appName)
    {
        $this->replyTo = $replyTo;
        $this->appName = $appName;
    }

    public function sendEmail($content = '<p>See Twig integration for better HTML integration!</p>', $subject = 'Hello world')
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            ->replyTo($this->replyTo)
            //->priority(Email::PRIORITY_HIGH)
            ->subject('welcome to'.$this->appName)
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');

        $this->mailer->send($email);

        // ...
    }

}