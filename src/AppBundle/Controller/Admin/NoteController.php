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
    * Delete a Note
    *
    * @Route("/admin/{objectOfTheNote}/{id}/note/{noteId}/supprimer", name="admin_note_delete", requirements={"objectOfTheNote": "animal|evenement|annonce", "id": "\d+", "noteId": "\d+"})
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function deleteAction($objectOfTheNote, $id, $noteId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $note = $em->getRepository('AppBundle:Note')->find($noteId);

        if (null === $note) {
            throw new NotFoundHttpException("La note d'id ".$noteId. " que vous souhaitez supprimer n'existe pas.");
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
                $route = 'admin_event_card';
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
