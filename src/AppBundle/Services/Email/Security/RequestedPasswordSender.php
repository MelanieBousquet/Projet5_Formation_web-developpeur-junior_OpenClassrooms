<?php

namespace AppBundle\Services\Email\Security;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

class RequestedPasswordSender 
{
    protected $mailer;
    
    protected $router;
    
    protected $tokenGenerator;
    
    protected $em;
    
    protected $session;

    public function __construct(\Swift_Mailer $mailer, RouterInterface $router, TokenGeneratorInterface $tokenGenerator, EntityManager $em, Session $session)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->tokenGenerator = $tokenGenerator;
        $this->em = $em;
        $this->session = $session;
    }

    public function resetPasswordLinkByEmail(UserInterface $user)
    {
        $userPseudo = $user->getUserPseudo();
        
        // If an email has not been already sent
        if (!$user->getIsAlreadyRequested() && null == $user->getConfirmationToken()){
            // Create a unique token for this user, generate the route with this token as parameter..
            $token = $this->tokenGenerator->generateToken();
            $requestLink = $this->router->generate('reset_password', ['token' => $token], true);
            $user->setConfirmationToken($token);
            $user->setIsAlreadyRequested(true);
            
            // ..then send it by email to the user..
            $message = \Swift_Message::newInstance()
                ->setCharset('UTF-8')
                ->setSubject('Demande de réinitialisation de mot de passe')
                ->setFrom('bousquet.melanie@gmail.com')
                ->setTo($user->getEmail())
                ->setBody("Bonjour '".$userPseudo."'! Voici le lien pour réinitialiser votre mot de passe : '".$requestLink."' . Site AssoTest ")
            ;
            $this->mailer->send($message);
            
            // ..then persist and flush it
            $this->em->persist($user);
            $this->em->flush();            
            $this->session->getFlashBag()->add('success', 'Un mail vous a bien été envoyé pour réinitialiser votre mot de passe');
            
        } else { // Error : an email has been already sent
            $this->session->getFlashBag()->add('alert' ,'Un email vous a déjà été envoyé avec un lien pour réinitialiser votre mot de passe. Vérifiez vos spams si vous n\'avez pas reçu ce mail.');
        }
        
    }
}