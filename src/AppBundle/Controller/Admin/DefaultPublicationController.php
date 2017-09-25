<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Publication;
use AppBundle\Form\DefaultPublicationType;
use AppBundle\Form\DefaultPublicationEditType;
use AppBundle\Entity\Note;
use AppBundle\Form\NoteType;
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
     * @Route("/admin/publications", name="admin_publications")
     * @Security("has_role('ROLE_FA')")
     */
    public function viewListAction(Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Publication')
        ;
        $publications = $repository->findDefaultPublications();
        var_dump($publications);

        return $this->render('admin/publication/default/viewList.html.twig', array(
            'publications' => $publications
        ));
    }    
    
    /**
     * View a publication
     *
     * @Route("/admin/publication/{id}/fiche", name="admin_publication_card", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_FA')")
     */
    public function viewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('AppBundle:Publication')->findOneById($id);
        $listNotes = $em->getRepository('AppBundle:Note')->findNotesbyObject('publication', $id);
        
        $note = new Note();
        $form = $this->get('form.factory')->create(NoteType::class, $note);
        $user = $this->get('security.token_storage')->getToken()->getUser();
            $note->setUser($user);
            $note->setPublication($publication);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($note);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Votre note a bien été ajoutée !');
            return $this->redirectToRoute('admin_publication_card', array('id' => $id));
        }
        
        return $this->render('admin/publication/default/view.html.twig', array(
            'publication' => $publication,
            'form' => $form->createView(),
            'listNotes' => $listNotes
        ));
    }
    
    /**
     * Add a publication
     *
     * @Route("/admin/publication/ajout", name="admin_publication_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = new Publication();
        $form = $this->get('form.factory')->create(DefaultPublicationType::class, $publication);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em->persist($publication);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Publication ajoutée !');

            return $this->redirectToRoute('admin_publications');

        }

        return $this->render('admin/publication/default/add.html.twig', array(
            'publication' => $publication,
            'form' => $form->createView()
        ));
    }
    
    /**
     * Edit a publication
     *
     * @Route("/admin/publication/{id}/edit", name="admin_publication_edit", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id, Request $request)
    {
        $publication = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Publication')
            ->find($id);

        $form = $this->get('form.factory')->create(DefaultPublicationEditType::class, $publication);

        if (null === $publication) {
            throw new NotFoundHttpException("L'événement d'id ".id. " n'existe pas.");
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($publication);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Publication modifiée !');

            return $this->redirectToRoute('admin_publications');

        }

        return $this->render('admin/publication/default/edit.html.twig', array(
            'publication' => $publication,
            'form' => $form->createView()
        ));
    }

    /**
     * Delete an publication
     *
     * @Route("/admin/publication/{id}/supprimer", name="admin_publication_delete", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('AppBundle:Publication')->find($id);

        if (null === $publication) {
            throw new NotFoundHttpException("L'événement d'id ".id. " n'existe pas.");
        }

        $em->remove($publication);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "La publication a bien été supprimée !");

        return $this->redirectToRoute('admin_publications');
    }

}
