<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Publication;
use AppBundle\Form\PublicationType;
use AppBundle\Form\PublicationEditType;
use AppBundle\Entity\Event;
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


class EventController extends Controller
{
    /**
     * List all events
     *
     * @Route("/admin/evenements", name="admin_events")
     * @Security("has_role('ROLE_FA')")
     */
    public function viewListAction(Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Publication')
        ;
        $events = $repository->findEventPublications();

        return $this->render('admin/publication/events/viewList.html.twig', array(
            'events' => $events
        ));
    }    
    
    /**
     * View an event
     *
     * @Route("/admin/evenement/{id}/fiche", name="admin_event_card", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_FA')")
     */
    public function viewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Publication')->findOneById($id);
        $mainImage = $em->getRepository('AppBundle:Image')->findMainImageByObject('publication', $id);
        $listNotes = $em->getRepository('AppBundle:Note')->findNotesbyObject('publication', $id);
        
        $note = new Note();
        $form = $this->get('form.factory')->create(NoteType::class, $note);
        $user = $this->get('security.token_storage')->getToken()->getUser();
            $note->setUser($user);
            $note->setPublication($event);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($note);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Votre note a bien été ajoutée !');
            return $this->redirectToRoute('admin_event_card', array('id' => $id));
        }
        
        return $this->render('admin/publication/events/view.html.twig', array(
            'event' => $event,
            'mainImage' => $mainImage,
            'form' => $form->createView(),
            'listNotes' => $listNotes
        ));
    }
    
    /**
     * Add an event
     *
     * @Route("/admin/evenement/ajout", name="admin_event_add")
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = new Publication();
        $form = $this->get('form.factory')->create(PublicationType::class, $publication);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            if ($publication->getImages()) {
                foreach($publication->getImages() as $image)
                {
                   $image->setPublication($publication);
                }
            }
            $em->persist($publication);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Evenement ajouté !');

            return $this->redirectToRoute('admin_events');

        }

        return $this->render('admin/publication/events/add.html.twig', array(
            'publication' => $publication,
            'form' => $form->createView()
        ));
    }
    
    /**
     * Edit an event
     *
     * @Route("/admin/evenement/{id}/edit", name="admin_event_edit", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id, Request $request)
    {
        $event = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Publication')
            ->find($id);

        $form = $this->get('form.factory')->create(PublicationEditType::class, $event);

        if (null === $event) {
            throw new NotFoundHttpException("L'événement d'id ".id. " n'existe pas.");
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Evenement modifié !q');

            return $this->redirectToRoute('admin_events');

        }

        return $this->render('admin/publication/events/edit.html.twig', array(
            'event' => $event,
            'form' => $form->createView()
        ));
    }

    /**
     * Delete an Event
     *
     * @Route("/admin/evenement/{id}/supprimer", name="admin_event_delete", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository('AppBundle:Publication')->find($id);

        if (null === $event) {
            throw new NotFoundHttpException("L'événement d'id ".id. " n'existe pas.");
        }

        $em->remove($event);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "L'événement a bien été supprimé !   ");

        return $this->redirectToRoute('admin_events');
    }

}
