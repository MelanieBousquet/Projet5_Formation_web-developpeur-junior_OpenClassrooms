<?php 

namespace AppBundle\Controller\Security;

use AppBundle\Entity\Password\ChangePassword;
use AppBundle\Form\ChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ChangePasswordController extends Controller 
{
    /**
     * Change Password
     *
     * @Route("/changement-motdepasse", name="change_password")
     */
    public function changePasswordAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        $data = new ChangePassword($this->getUser());
        $form = $this->createForm(ChangePasswordType::class, $data);
 
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $user = $data->getUser(); 
            
            if ($encoder->isPasswordValid($this->getUser(), $data->getOldPassword())) {
                $user->setPlainPassword($data->getNewPassword());

                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('success', 'Le mot de passe a bien été modifié.');

                return $this->redirectToRoute('home');
                }
            else {
                $this->addFlash('error', 'L\'ancien mot de passe n\'est pas valide.');
            }
        }
 
        return $this->render('security/changepassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}