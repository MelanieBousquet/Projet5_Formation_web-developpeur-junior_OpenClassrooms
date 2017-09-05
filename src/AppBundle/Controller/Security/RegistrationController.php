<?php 


namespace AppBundle\Controller\Security;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use AppBundle\Event\UserRegistrationEvent;
use AppBundle\Event\AppBundleEvents;
use AppBundle\Services\Email\Security\RegistrationListener;
use AppBundle\Entity\UserListener;
use AppBundle\Services\Email\Security\RegistrationConfirmationMailSender;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            //Encode the password 
            // $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            // $user->setPassword($password);
            
            // save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
           
            // send confirmation email (event creation and dispatch)
            $event = new UserRegistrationEvent($user);
            $dispatcher = $this->get('event_dispatcher');
            //$listener = new RegistrationListener();
            //$dispatcher->addListener(AppBundleEvents::USER_REGISTERED, array($listener, 'processConfirmation'));
            $dispatcher->dispatch(AppBundleEvents::USER_REGISTERED, $event);
          //  $emailUser = $user->getEmail();
       // $pseudoUser = $user->getUserPseudo();
        
       // $message = \Swift_Message::newInstance()
        /*  ->setSubject("AssoTest : Confirmation de la création de votre compte")
          ->setFrom('bousquet.melanie@gmail.com')
          ->setTo($emailUser)
          ->setBody("Bienvenue sur le site AssoTest '".$pseudoUser."'! ")
        ;

        $mailer->send($message); */

            // Set a "flash" success message for the user
            $request->getSession()->getFlashBag()->add('Info', 'Compte utilisateur bien créé, un email de confirmation vous a été envoyé');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/register.html.twig', array('form' => $form->createView()));
    }
}
