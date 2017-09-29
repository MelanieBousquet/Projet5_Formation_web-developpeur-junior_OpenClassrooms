<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Contact;
use AppBundle\Form\ContactType;
use AppBundle\Entity\Animal;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ContactController extends Controller
{
    /**
    * Edit a Contact
    *
    * @Route("/admin/contact", name="admin_contact_edit")
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function editAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository('AppBundle:Contact')->findSingleContact();

        $form = $this->get('form.factory')->create(ContactType::class, $contact);

        if (null === $contact) {
            throw new NotFoundHttpException("Cette page n'existe pas");
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($contact);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Coordonnées de contact modifiées !');
            
            return $this->redirectToRoute('admin_contact_edit');
        }

        return $this->render('admin/contact/edit.html.twig', array(
            'contact' => $contact,
            'form' => $form->createView()
        ));
    }

}
