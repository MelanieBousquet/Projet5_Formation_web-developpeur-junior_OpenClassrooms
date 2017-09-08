<?php

namespace AppBundle\Services\Email\Security;

use AppBundle\Event\UserEvent;

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
    
    public function processConfirmation(UserEvent $event)
    {
        $this->registrationConfirmMail->confirmByEmail($event->getUser());
    }
    
}