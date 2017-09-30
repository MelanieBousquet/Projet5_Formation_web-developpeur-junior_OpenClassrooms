<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Publication;
use AppBundle\Form\PublicationType;
use AppBundle\Form\PublicationEditType;
use AppBundle\Entity\Event;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class EventController extends Controller
{
    /**
     * List all events
     *
     * @Route("/evenements/{page}", name="front_events", defaults={"page": "1"}, requirements={"page": "\d+"})
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
        $events = $repository->findEventPublications($page, 'published');
        
        // Count the total number of pages
        $nbPages = ceil(count($events) / ($nbPerPages = 10));
        // And return 404 error if the page doesn't exist
        if ($page > $nbPages) {
          throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('front/event/viewList.html.twig', array(
            'events' => $events,
            'nbPages' => $nbPages,
            'page' => $page,
            
        ));
    }    
    
    /**
     * View an event
     *
     * @Route("/evenement/{id}/fiche", name="front_event_card", requirements={"id": "\d+"})
     */
     
    public function viewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Publication')->findOneById($id);
        
        if (null === $event) {
            throw new NotFoundHttpException("L'événement que vous souhaitez consulter n'existe pas.");
        }

        
        return $this->render('front/event/view.html.twig', array(
            'event' => $event
        ));
    }
    
}
