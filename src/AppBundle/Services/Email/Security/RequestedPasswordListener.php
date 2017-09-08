<?php

namespace AppBundle\Services\Email\Security;

use AppBundle\Event\UserEvent;

class RequestedPasswordListener
{
    protected $requestedPasswordSender;
    
    /**
     * @param $registrationConfirmationMail
     */
    public function __construct(RequestedPasswordSender $requestedPasswordSender)
    {
        $this->requestedPasswordSender = $requestedPasswordSender;
    }
    
    public function processPasswordRequest(UserEvent $event)
    {
        $this->requestedPasswordSender->resetPasswordLinkByEmail($event->getUser());
    }
    
}