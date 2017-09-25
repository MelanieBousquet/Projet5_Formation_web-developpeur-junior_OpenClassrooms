<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Animal;
use AppBundle\Entity\Publication;
use AppBundle\Entity\State;
use AppBundle\Entity\AnimalState;
use AppBundle\Entity\Image;
use AppBundle\Form\PublicationOnAnimalType;
use AppBundle\Form\AnimalPublicationImageType;
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


class PublicationOnAnimalController extends Controller
{  
    /**
     * View a specific publication of animalstate and edit images
     *
     * @Route("/admin/animal/{state}/{animalStateId}/publication/{publicationId}/fiche", name="admin_animal_publication_card", requirements={"animalStateId": "\d+", "publicationId": "\d+"})
     */
    public function viewAction($state, $animalStateId, $publicationId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $imgRepository = $em->getRepository('AppBundle:Image');
        $publication = $em->getRepository('AppBundle:Publication')->findOneBy(array('id' => $publicationId));
        $animalImages = $imgRepository->findBy(array('animal' => $publication->getAnimalState()->getAnimal()));
        
        $image = new Image();
        $form = $this->get('form.factory')->create(AnimalPublicationImageType::class, $image);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $image->setPublication($publication);
            $em->persist($image);
            $em->persist($publication);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Publication mise à jour !');

            return $this->redirectToRoute('admin_animal_publication_card', array('state' => $state, 'animalStateId' => $animalStateId, 'publicationId' => $publicationId ));
        }

        return $this->render('admin/publication/animal/view.html.twig', array(
            'publication' => $publication,
            'animalImages' => $animalImages,
            'form' => $form->createView()
        ));
    }
    
    /**
     * Add an animal image to the publication
     *
     * @Route("/admin/animal/publication/{publicationId}/image/{imageId}", name="admin_animal_publication_add_image", requirements={"publicationId" : "\d+", "imageId" : "\d+"})
     */
    public function addImageAction($publicationId, $imageId, Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('AppBundle:Image')->findOneBy(array('id'=> $imageId));
        $publication = $em->getRepository('AppBundle:Publication')->findOneBy(array('id' => $publicationId));
        
        $publication->addImage($image);
        $em->persist($image);
        $em->persist($publication);
        $em->flush();
        
        return $this->redirectToRoute('admin_animal_publication_card', array(
            'state' => $publication->getAnimalState()->getState()->getType(), 
            'animalStateId' => $publication->getAnimalState()->getId(), 
            'publicationId' => $publicationId
        ));
    }
    
    /**
     * Add a publication on animal (depending on the animal state)
     *
     * @Route("/admin/animal/{state}/{animalStateId}/publication/ajout", name="admin_animal_publication_add", requirements={"animalStateId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAction($state, $animalStateId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $animalState = $em->getRepository('AppBundle:AnimalState')->findOneById($animalStateId);
        $publication = new Publication();
        $publication->setAnimalState($animalState);
        $animal = $publication->getAnimalState()->getAnimal();
        $title = $animal->getName();
        $publication->setTitle($title);
        $form = $this->get('form.factory')->create(PublicationOnAnimalType::class, $publication);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
                foreach($animal->getImages() as $image) {
                    $publication->addImage($image);
                }
            $em->persist($publication);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Supprimer les photos non souhaitées ou en ajouter, puis publier la publication !');

            return $this->redirectToRoute('admin_animal_publication_card', array('state' => $state, 'animalStateId' => $animalStateId, 'publicationId' => $publication->getId()));
        }

        return $this->render('admin/publication/animal/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * Edit a animal
    *
    * @Route("/admin/animaux/{id}/edit", name="admin_animal_edit", requirements={"id": "\d+"})
    * @Security("has_role('ROLE_ADMIN')")
    
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $animal = $em->getRepository('AppBundle:Animal')->find($id);

        $form = $this->get('form.factory')->create(AnimalEditType::class, $animal);

        if (null === $animal) {
            throw new NotFoundHttpException("L'animal d'id ".id. " n'existe pas.");
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
             if ($animal->getAnimalStates()) {
                foreach($animal->getAnimalStates() as $animalState) {
                    $animalState->setAnimal($animal);
                }
            }            
        
            $em->persist($animal);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Animal bien édité');

            return $this->redirectToRoute('admin_animal_card', array('id' => $animal->getId()));

        }

        return $this->render('admin/animal/edit.html.twig', array(
            'animal' => $animal,
            'form' => $form->createView()
        ));
    }

    /**
     * Delete a Animal
     *
     * @Route("/admin/animaux/{id}/supprimer", name="admin_animal_delete", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
    
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $animal = $em->getRepository('AppBundle:Animal')->find($id);

        if (null === $animal) {
            throw new NotFoundHttpException("L'animal d'id ".id. " n'existe pas.");
        }

        $em->remove($animal);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "L'animal a bien été supprimé !");

        return $this->redirectToRoute('adminhome');

    }
*/
}
