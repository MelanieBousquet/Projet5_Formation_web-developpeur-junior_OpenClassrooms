<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Page;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class MenuController extends Controller
{
    /**
     * List all name pages for the menu, depending on a category
     */
    public function listPagesAction($categoryName, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pages = $em->getRepository('AppBundle:Page')->getPublishedPagesByCategory($categoryName);
        
        return $this->render('front/menu.html.twig', array(
            'pages' => $pages,
            'categoryName' => $categoryName
        ));
    }    
    
}
