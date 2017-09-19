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
    * @Route("/", name="adminhome")
    */
    public function viewAction()
    {
        return $this->render('admin/home.html.twig');
    }

}
