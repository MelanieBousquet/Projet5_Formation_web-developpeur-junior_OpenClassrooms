<?php

namespace AppBundle\Controller\Security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\RequestPasswordType;
use AppBundle\Entity\Password\RequestPassword;
use AppBundle\Entity\User;
use AppBundle\Event\UserEvent;
use AppBundle\Event\AppBundleEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RequestPasswordController extends Controller 
{
    /**
     * @Route("/demande-reinitialisation-motdepasse", name="request_password")
     */
    public function requestPasswordAction(Request $request)
    {
        $form = $this->get('form.factory')->create(RequestPasswordType::class, new RequestPassword()) ;
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $identifier = $form->get('identifier')->getData();
            $user = $em->getRepository('AppBundle:User')->getUserByEmailOrPseudo($identifier);
            
            // Event dispatch -> service RequestedPasswordSender called 
            // => send an email with link for resetting password with token - exception if an email has already been sent without resetting
            $event = new UserEvent($user);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(AppBundleEvents::PASSWORD_REQUESTED, $event);

            return $this->redirectToRoute('home');
            
        }
        
        return $this->render('security/request-password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}