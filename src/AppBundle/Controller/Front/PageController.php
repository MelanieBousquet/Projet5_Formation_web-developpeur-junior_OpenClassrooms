<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class PageController extends Controller
{
    /**
     * View a page
     *
     * @Route("/categorie/{categoryName}/{id}", name="front_page_card", requirements={"id": "\d+"})
     */
     
    public function viewAction($categoryName, $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('AppBundle:Page')->findOneById($id);
        
        if (null === $page) {
            throw new NotFoundHttpException("La page que vous souhaitez consulter n'existe pas.");
        }
        
        return $this->render('front/page/view.html.twig', array(
            'page' => $page
        ));
    }
    
}
