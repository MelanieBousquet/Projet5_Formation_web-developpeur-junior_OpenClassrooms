<?php

/* Home Page */

namespace AppBundle\Controller\Front;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HomeController extends Controller
{
    /**
    * Index
    *
    * @Route("/", name="front_home")
    */
    public function viewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Publication');
        $animalsOnAdoption = $repository->findAnimalsOnAdoptionForHomePage();
        $animalsLost = $repository->findAnimalsLostForHomePage();
        $animalsFound = $repository->findAnimalsFoundForHomePage();
        $lastEvent = $repository->findLastEventForHomePage();
        $lastPublications = $repository->findLastPublicationsForHomePage();
        
        return $this->render('front/home.html.twig', array(
            'animalsOnAdoption' => $animalsOnAdoption,
            'animalsLost' => $animalsLost,
            'animalsFound' => $animalsFound,
            'lastEvent' => $lastEvent,
            'lastPublications' => $lastPublications
        ));
    }

}
