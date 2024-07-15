<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendEmail($to, $subject, $content)
    {
        $email = (new Email())
            ->from('contact@zoodarcadia.fr')
            ->to($to)
            ->subject($subject)
            ->text($content);  

        try {
            $this->mailer->send($email);
            echo "Email sent successfully";
        } catch (\Exception $e) {
            echo "Failed to send email: " . $e->getMessage();
        }
    }
}
