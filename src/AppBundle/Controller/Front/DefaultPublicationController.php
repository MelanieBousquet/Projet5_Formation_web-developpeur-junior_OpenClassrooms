<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Publication;
use AppBundle\Form\DefaultPublicationType;
use AppBundle\Form\DefaultPublicationEditType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class DefaultPublicationController extends Controller
{
    /**
     * List all publications
     *
     * @Route("/front/publications/{page}", name="front_publications", defaults={"page": "1"}, requirements={"page": "\d+"})
     */
    public function viewListAction($page, Request $request)
    {
        if ($page < 1) {
          throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }
        
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Publication')
        ;
        $publications = $repository->findDefaultPublications($page);
        
        // Count the total number of pages
        $nbPages = ceil(count($publications) / ($nbPerPages = 10));
        // And return 404 error if the page doesn't exist
        if ($page > $nbPages) {
          throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('front/publication/viewList.html.twig', array(
            'publications' => $publications,
            'nbPages' => $nbPages,
            'page' => $page,
            
        ));
    }    
    
    /**
     * View a publication
     *
     * @Route("/front/publication/{id}/fiche", name="front_publication_card", requirements={"id": "\d+"})
     */
     
    public function viewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('AppBundle:Publication')->findOneById($id);
        
        return $this->render('front/publication/view.html.twig', array(
            'publication' => $publication
        ));
    }
    
}
