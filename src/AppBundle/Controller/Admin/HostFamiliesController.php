<?php 

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\User;

class HostFamiliesController extends Controller 
{
    /**
     * @Route("admin/familles-daccueil", name="admin_host_families")
     * @Security("has_role('ROLE_FA')")
     */
    public function viewAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $listFamilies = $em->getRepository('AppBundle:User')->getUsersWithSpecificRole('ROLE_FA');
        $listAnimalsAlone = $em->getRepository('AppBundle:Animal')->getAnimalsAlone();
        
        return $this->render('admin/users/host-families.html.twig', array(
            'listFamilies' => $listFamilies,
            'listAnimalsAlone' => $listAnimalsAlone
        ));
    }
    
    
    /**
     * @Route("admin/famille-daccueil/{userId}/ajout-animal/{animalId}", name="admin_host_families_add_animal", requirements={"userId": "\d+", "animalId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAnimalAction($userId, $animalId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(array('id' => $userId));
        $animal = $em->getRepository('AppBundle:Animal')->findOneBy(array('id' => $animalId));
        
        $user->addAnimal($animal);
        $em->persist($user);
        $em->flush();
        
        $request->getSession()->getFlashBag()->add('info', "L'animal a bien été ajouté à sa nouvelle famille d'accueil !");
        
        return $this->redirectToRoute('admin_host_families');
    }
    
    /**
     * @Route("admin/famille-daccueil/{userId}/retrait-animal/{animalId}", name="admin_host_families_remove_animal", requirements={"userId": "\d+", "animalId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function removeAnimalAction($userId, $animalId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findOneBy(array('id' => $userId));
        $animal = $em->getRepository('AppBundle:Animal')->findOneBy(array('id' => $animalId));
        
        $user->removeAnimal($animal);
        $animal->setUser();
        $em->persist($user);
        $em->persist($animal);
        $em->flush();
        
        $request->getSession()->getFlashBag()->add('info', "L'animal a bien été supprimé de la famille d'accueil !");
        
        return $this->redirectToRoute('admin_host_families');
    }
    
}