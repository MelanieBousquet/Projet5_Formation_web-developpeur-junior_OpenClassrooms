<?php

namespace AppBundle\Controller\Security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
//use AppBundle\Form\ResetPasswordType;
//use AppBundle\Entity\Password\ResetPassword;
use AppBundle\Entity\User;
use AppBundle\Event\UserEvent;
use AppBundle\Event\AppBundleEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ResetPasswordController extends Controller
{
    /**
     * @Route("/reinitialisation-motdepasse", name="reset_password")
     */
    public function resetPasswordAction(Request $request)
    {
        
    }
    
}