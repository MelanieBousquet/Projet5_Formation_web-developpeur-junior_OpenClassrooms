<?php

namespace AppBundle\Entity\Password;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints\EmailOrPseudoUser;
use AppBundle\Validator\Constraints as AppBundleAssert;

class RequestPassword
{
    /**
     *
     * @var UserInterface
     */
    private $user;

    /**
     * @AppBundleAssert\EmailOrPseudoUser
     */
    private $identifier;

    public function getIdentifier()
    {
        return $this->identifier;
    }

    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }

    function getUser()
    {
        return $this->user;
    }

    function setUser(UserInterface $user)
    {
        $this->user = $user;
    }
}