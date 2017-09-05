<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\User;

class UserListener
{
    /**
     * @var \Symfony\Component\Security\Core\Encoder\EncoderFactory
     */
    private $encoderFactory;

    /**
     * @param \Symfony\Component\Security\Core\Encoder\EncoderFactory $encoderFactory
     */
    public function __construct(EncoderFactory $encoderFactory)
    {
      $this->encoderFactory = $encoderFactory;
    }
    
    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        $encodedPassword = $this->encoderFactory->getEncoder($user)->encodePassword($user->getPlainPassword(), $user->getSalt());
        $user->setPassword($encodedPassword);
        
    }
    
    public function preUpdate(User $user, LifecycleEventArgs $event)
    {
        $encodedPassword = $this->encoderFactory->getEncoder($user)->encodePassword($user->getNewValue('plainPassword'));
        $user->setNewValue('password', $encodedPassword);
        
    }
}