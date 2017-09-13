<?php 

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\User;

class UsersRolesController extends Controller
{
    /**
     * @Route("/admin/gestion-utilisateurs", name="admin_users")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewUsersListAction(Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:User')
        ;
        
        $listAdmins = $repository->getUsersWithSpecificRole('ROLE_ADMIN');
        $listFamilies = $repository->getUsersWithSpecificRole('ROLE_FA');
        $listDefaultUsers = $repository->getUsersWithSpecificRole('ROLE_USER');
        
        return $this->render('admin/users/usersRoles.html.twig', array(
            'listAdmins' => $listAdmins,
            'listFamilies' => $listFamilies,
            'listDefaultUsers' => $listDefaultUsers
        ));
    }
    
    /**
     * @Route("/admin/{id}/{role}/ajout-role-utilisateur", name="admin_set_user_role", requirements={"id": "\d+", "role": "\w+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function setRoleAction($id, $role, Request $request)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $repository = $em
            ->getRepository('AppBundle:User');
        
        $user = $repository->findOneById($id);
        $listAdmins = $repository->getUsersWithSpecificRole('ROLE_ADMIN');
        $listFamilies = $repository->getUsersWithSpecificRole('ROLE_FA');
        $listDefaultUsers = $repository->getUsersWithSpecificRole('ROLE_USER');
        
        $user->setRoles(array($role));
        $em->persist($user);
        $em->flush();        
        
        $request->getSession()->getFlashBag()->add('Info', 'Rôle de l\'utilisateur bien mis à jour !');
        
        return $this->redirectToRoute('admin_users', array(
            'listAdmins' => $listAdmins,
            'listFamilies' => $listFamilies,
            'listDefaultUsers' => $listDefaultUsers
        ));
    }
}