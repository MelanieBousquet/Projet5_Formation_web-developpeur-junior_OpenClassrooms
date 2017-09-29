<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Publication;
use AppBundle\Entity\Animal;
use AppBundle\Entity\Type;
use AppBundle\Entity\Breed;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Date;

class FoundOrLostAnimalController extends Controller
{
    /**
     * List of animals found or lost
     *
     * @Route("/animaux/{state}", name="front_list_animal_found_lost", requirements={"state": "trouvé|perdu"})
     */
    public function viewListAction($state, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $repoPublication = $em->getRepository('AppBundle:Publication');
        
        $publicationsFoundOrLost = $repoPublication->findPublicationsFoundOrLost($state) ;
        
        return $this->render('front/animal/foundLost/viewList.html.twig', array(
            'publicationsFoundOrLost' => $publicationsFoundOrLost
        ));
    }
    
    /**
     * View an animal found or lost
     *
     * @Route("/animal/{state}/{id}/fiche", name="front_animal_found_lost_card", requirements={"id": "\d+"})
     */
    public function viewAnimalAction($state, $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('AppBundle:Publication')->findOneById($id);
        
        return $this->render('front/animal/foundLost/viewAnimal.html.twig', array(
            'publication' => $publication
        ));
    }
    
}