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
use Symfony\Component\EventDispatcher\EventDispatcher;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            // send confirmation email (event creation and dispatch)
            $event = new UserRegistrationEvent($user);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch(AppBundleEvents::USER_REGISTERED, $event);

            // Set a "flash" success message for the user
            $request->getSession()->getFlashBag()->add('Info', 'Compte utilisateur bien créé, un email de confirmation vous a été envoyé');

            return $this->redirectToRoute('home');
        }

        return $this->render('security/register.html.twig', array('form' => $form->createView()));
    }
}
