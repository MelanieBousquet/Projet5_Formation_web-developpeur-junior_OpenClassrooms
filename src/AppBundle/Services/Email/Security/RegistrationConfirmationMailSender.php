<?php

namespace AppBundle\Services\Email\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class RegistrationConfirmationMailSender
{
    protected $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function confirmByEmail(UserInterface $user)
    {
        $emailUser = $user->getEmail();
        $pseudoUser = $user->getUserPseudo();
        
        $message = \Swift_Message::newInstance()
          ->setSubject("AssoTest : Confirmation de la création de votre compte")
          ->setFrom('bousquet.melanie@gmail.com')
          ->setTo($emailUser)
          ->setBody("Bienvenue sur le site AssoTest '".$pseudoUser."'! ")
        ;

        $this->mailer->send($message);
    }
}