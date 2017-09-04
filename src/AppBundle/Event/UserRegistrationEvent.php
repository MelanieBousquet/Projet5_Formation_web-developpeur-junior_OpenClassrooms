<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRegistrationEvent extends Event
{    
    protected $user;
    
    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
    
    public function getUser()
    {
        return $this->user;
    }
}