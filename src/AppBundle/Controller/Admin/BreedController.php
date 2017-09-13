<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Breed;
use AppBundle\Entity\Type;
use AppBundle\Form\BreedType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class BreedController extends Controller
{

    /**
    * List all breeds and Add a breed
    *
    * @Route("/admin/races", name="admin_breeds")
    */
    public function addAction(Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Breed')
        ;
        $catBreeds = $repository->getCatBreeds();
        $dogBreeds = $repository->getDogBreeds();

        $breed = new Breed();
        $form = $this->get('form.factory')->create(BreedType::class, $breed);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($breed);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Race ajoutée');

            return $this->redirectToRoute('admin_breeds');
        }

        return $this->render('admin/breed/add.html.twig', array(
            'form' => $form->createView(),
            'catBreeds' => $catBreeds,
            'dogBreeds' => $dogBreeds
        ));
    }

    /**
    * Edit a breed
    *
    * @Route("/admin/race/{id}/edit", name="admin_breed_edit", requirements={"id": "\d+"})
    */
    public function editAction($id, Request $request)
    {
        $breed = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Breed')
            ->find($id);

        $form = $this->get('form.factory')->create(BreedType::class, $animal);

        if (null === $breed) {
            throw new NotFoundHttpException("La race d'id ".id. " n'existe pas.");
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($breed);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Race modifiée');

            return $this->redirectToRoute('admin_breeds');

        }

        return $this->render('admin/breed/edit.html.twig', array(
            'breed' => $breed,
            'form' => $form->createView()
        ));
    }

    /**
    * Delete a Breed
    *
    * @Route("/admin/race/{id}/supprimer", name="admin_breed_delete", requirements={"id": "\d+"})
    */
    public function deleteAction($id, Request $request)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $breed = $em
            ->getRepository('AppBundle:Breed')
            ->find($id);

        if (null === $breed) {
            throw new NotFoundHttpException("La race d'id ".id. " n'existe pas.");
        }

        $em->remove($breed);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "La race a bien été supprimée.");

        return $this->redirectToRoute('admin_breeds');
    }

}
