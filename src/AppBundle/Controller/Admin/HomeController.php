<?php

namespace AppBundle\Controller\Admin;

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
    * @Route("/admin/", name="admin_home")
    */
    public function viewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Note');
        $lastNotes =  $repository->findLastNotes();
        
        return $this->render('admin/home.html.twig', array('lastNotes' => $lastNotes));
    }

}
