<?php

namespace AppBundle\Controller\Security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ResetPasswordType;
use AppBundle\Entity\User;
use AppBundle\Event\UserEvent;
use AppBundle\Event\AppBundleEvents;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class ResetPasswordController extends Controller
{
    /**
     * @Route("/reinitialisation-motdepasse", name="reset_password")
     */
    public function resetPasswordAction(Request $request, EncoderFactory $encoderFactory)
    {
        $em = $this->getDoctrine()->getManager();
        $token = $request->query->get('token');
        $user = $em->getRepository('AppBundle:User')->findOneByConfirmationToken($token);
        
        if ($user)  {
            $form = $this->get('form.factory')->create(ResetPasswordType::class, $user);

            if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                $user->setIsAlreadyRequested(false);
                $user->setConfirmationToken(null);
                $encodedPassword = $encoderFactory->getEncoder($user)->encodePassword($form->getData()->getPlainPassword(), $user->getSalt());
                $user->setPassword($encodedPassword);
                $em->persist($user);
                $em->flush();

                $request->getSession()->getFlashBag()->add('info', 'Votre nouveau mot de passe a bien été enregistré ! Reconnectez-vous avec vos nouveaux identifiants.');

                return $this->redirectToRoute('login');
            }
            
        } else {
            $request->getSession()->getFlashBag()->add('info', 'Lien de réinitialisation de mot de passe incorrect, veuillez vérifier le lien envoyé par mail.');
            return $this->redirectToRoute('login');
        }
        
        return $this->render('security/reset-password.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    
}