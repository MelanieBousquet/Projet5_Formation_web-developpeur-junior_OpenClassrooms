<?php 

namespace AppBundle\Controller\Security;

use AppBundle\Entity\Password\ChangePassword;
use AppBundle\Form\ChangePasswordType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

class ChangePasswordController extends Controller 
{
    /**
     * Change Password
     *
     * @Route("/changement-motdepasse", name="change_password")
     */
    public function changePasswordAction(Request $request, UserPasswordEncoderInterface $encoder, EncoderFactory $encoderFactory)
    {
        $data = new ChangePassword($this->getUser());
        $form = $this->createForm(ChangePasswordType::class, $data);
 
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $user = $data->getUser(); 
            
            if ($encoder->isPasswordValid($this->getUser(), $data->getOldPassword())) {
                $user->setPlainPassword($data->getNewPassword());
                $encodedPassword = $encoderFactory->getEncoder($user)->encodePassword($data->getNewPassword(), $user->getSalt());
                $user->setPassword($encodedPassword);
                
                $manager = $this->getDoctrine()->getManager();
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('info', 'Le mot de passe a bien été modifié.');

                return $this->redirectToRoute('front_home');
                }
            else {
                $this->addFlash('info', 'L\'ancien mot de passe n\'est pas valide.');
            }
        }
 
        return $this->render('security/changepassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}