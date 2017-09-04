<?php

namespace AppBundle\Services\Email\Security;

use AppBundle\Event\UserRegistrationEvent;

class RegistrationListener
{
    protected $registrationConfirmMail;
    
    /**
     * @param $registrationConfirmationMail
     */
    public function __construct(RegistrationConfirmationMailSender $registrationConfirmMail)
    {
        $this->registrationConfirmMail = $registrationConfirmMail;
    }
    
    public function processConfirmation(UserRegistrationEvent $event)
    {
        $this->registrationConfirmMail->confirmByEmail($event->getUser());
    }
    
}