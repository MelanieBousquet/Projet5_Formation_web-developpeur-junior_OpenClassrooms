<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Page;
use AppBundle\Entity\Category;
use AppBundle\Form\PageType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;


class PageController extends Controller
{
    /**
     * List of pages depending on category
     *
     * @Route("/admin/category/{categoryId}/pages", name="admin_pages", requirements={"categoryId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewListAction($categoryId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $pageRepo = $em->getRepository('AppBundle:Page');
        $listPages = $pageRepo->getPagesByCategory($categoryId);
        $categoryRepo = $em->getRepository('AppBundle:Category');
        $category = $categoryRepo->find($categoryId);

        return $this->render('admin/page/viewList.html.twig', array(
            'listPages' => $listPages,
            'category' => $category
        ));
    }
    
    /**
     * View / Edit a specific page
     *
     * @Route("/admin/category/{categoryId}/page/{id}", name="admin_page_card", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewAndEditAction($categoryId, $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $page = $em->getRepository('AppBundle:Page')->find($id);
        
        if (null === $page) {
            throw new NotFoundHttpException("La page d'id ".id. " n'existe pas.");
        }
            
        $form = $this->get('form.factory')->create(PageType::class, $page);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($page);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'La page a bien été modifiée !');
            return $this->redirectToRoute('admin_pages', array('category' => $category));
        }

        return $this->render('admin/page/viewEdit.html.twig', array(
            'page' => $page,
            'form' => $form->createView()
        ));
    }

    /**
     * Add a page
     *
     * @Route("/admin/category/{categoryId}/page/ajout", name="admin_page_add", requirements={"categoryId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction($categoryId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('AppBundle:Category')->find($categoryId);
         
        $page = new Page();
        $page->setCategory($category);
        $form = $this->get('form.factory')->create(PageType::class, $page);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            
            
            $em->persist($page);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Page bien ajoutée');

            return $this->redirectToRoute('admin_pages', array('categoryId' => $categoryId));
        }

        return $this->render('admin/page/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Delete a Page
     *
     * @Route("/admin/category/{categoryId}/page/{id}/supprimer", name="admin_page_delete", requirements={"categoryId": "\d+", "id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($categoryId, $id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $page = $em->getRepository('AppBundle:Page')->find($id);

        if (null === $page) {
            throw new NotFoundHttpException("La page d'id ".id. " n'existe pas.");
        }

        $em->remove($page);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "La page a bien été supprimée !");

        return $this->redirectToRoute('admin_pages', array('categoryId' => $categoryId));

    }

}
