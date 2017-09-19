<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Note;
use AppBundle\Form\NoteType;
use AppBundle\Entity\Animal;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class NoteController extends Controller
{
    /**
    * Edit a note
    *
    * @Route("/admin/{objectOfTheNote}/{id}/note/{noteId}/edit", name="admin_note_edit", requirements={"objectOfTheNote": "animal|evenement|annonce", "id": "\d+", "noteId": "\d+"})
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function editAction($objectOfTheNote, $id, $noteId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $note->getRepository('AppBundle:Note')->find($id);

        $form = $this->get('form.factory')->create(NoteType::class, $note);

        if (null === $note) {
            throw new NotFoundHttpException("La note d'id ".id. " n'existe pas.");
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($note);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Note modifiée !');
            
            switch ($objectOfTheNote) {
                case 'animal' :
                    $route = 'admin_animal_card';
                    break;
                case 'evenement' : 
                    $route = 'admin_evenement_card';
                    break;
                case 'annonce' :
                    $route = 'admin_annonce_card';
                    break; 
                default:
                    $route = 'home';
            }
            return $this->redirectToRoute($route, array('id' => $id));
        }

        return $this->render('admin/note/edit.html.twig', array(
            'note' => $note,
            'form' => $form->createView()
        ));
    }

    /**
    * Delete a Breed
    *
    * @Route("/admin/{objectOfTheNote}/{id}/note/{noteId}/supprimer", name="admin_note_delete", requirements={"objectOfTheNote": "animal|evenement|annonce", "id": "\d+", "noteId": "\d+"})
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function deleteAction($objectOfTheNote, $id, $noteId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('AppBundle:Note')->find($noteId);

        if (null === $note) {
            throw new NotFoundHttpException("La note d'id ".$noteId. " n'existe pas.");
            return ;
        }

        $em->remove($note);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "La note a bien été supprimée.");

        switch ($objectOfTheNote) {
            case 'animal' :
                $route = 'admin_animal_card';
                break;
            case 'evenement' : 
                $route = 'admin_evenement_card';
                break;
            case 'annonce' :
                $route = 'admin_annonce_card';
                break; 
            default:
                $route = 'home';
        }
        return $this->redirectToRoute($route, array('id' => $id));
    }

}
