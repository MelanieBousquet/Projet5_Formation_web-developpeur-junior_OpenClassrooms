<?php 

namespace AppBundle\Controller\Security;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use AppBundle\Entity\User;
use AppBundle\Form\UserEditType;

class AccountController extends Controller 
{
    /**
     * View account
     *
     * @Route("/compte", name="user_account")
     * @Security("has_role('ROLE_USER')")
     */
    public function viewAccountAction(Request $request)
    {
        $user = $this->getUser();
        
        return $this->render('security/account.html.twig', array(
            'user' => $user
        ));
    }    
    
    /**
     * Manage account
     *
     * @Route("/compte/edit", name="user_account_edition")
     * @Security("has_role('ROLE_USER')")
     */
    public function editAccountAction(Request $request)
    {
        $user = $this->getUser();
        
        $form = $this->get('form.factory')->create(UserEditType::class, $user);
 
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            $request->getSession()->getFlashBag()->add('info', 'Les informations du compte ont bien été modifiées.');

            return $this->redirectToRoute('user_account');
        } 
 
        return $this->render('security/edit-account.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
}