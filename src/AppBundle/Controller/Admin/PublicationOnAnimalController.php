<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Animal;
use AppBundle\Entity\Publication;
use AppBundle\Entity\State;
use AppBundle\Entity\AnimalState;
use AppBundle\Entity\Image;
use AppBundle\Form\PublicationOnAnimalType;
use AppBundle\Form\PublicationOnAnimalWithPlaceType;
use AppBundle\Form\ObjectImageType;
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
        $form = $this->get('form.factory')->create(ObjectImageType::class, $image);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $animal = $publication->getAnimalState()->getAnimal();
            $image->addPublication($publication);
            $animal->addImage($image);
            
            $em->persist($publication);
            $em->persist($animal);
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
        $state = $publication->getAnimalState()->getState()->getType();
        $animalStateId = $publication->getAnimalState()->getId();
        
        if (null === $image) {
            throw new NotFoundHttpException("L'image d'id ".id. " n'existe pas.");
        }
        
        if ($image->getPublications()) {
            foreach($image->getPublications() as $publication) {
                if ($publication->getId() == $publicationId) {
                    $request->getSession()->getFlashBag()->add('info', 'Cette image est déjà liée à la publication !');

                    return $this->redirectToRoute('admin_animal_publication_card', array('state' => $state, 'animalStateId' => $animalStateId, 'publicationId' => $publicationId ));
                }
            }
        }
        $publication->addImage($image);
        $em->persist($image);
        $em->persist($publication);
        $em->flush();
        
        $request->getSession()->getFlashBag()->add('info', 'Image ajoutée à la publication !');
        
        return $this->redirectToRoute('admin_animal_publication_card', array(
            'state' => $state, 
            'animalStateId' => $animalStateId, 
            'publicationId' => $publicationId
        ));
    }
    
    /**
     * Remove an animal image from the publication
     *
     * @Route("/admin/animal/publication/{publicationId}/image/{imageId}/supprimer", name="admin_animal_publication_remove_image", requirements={"publicationId" : "\d+", "imageId" : "\d+"})
     */
    public function removeImageAction($publicationId, $imageId, Request $request) 
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('AppBundle:Image')->findOneBy(array('id'=> $imageId));
        $publication = $em->getRepository('AppBundle:Publication')->findOneBy(array('id' => $publicationId));
        $state = $publication->getAnimalState()->getState()->getType();
        $animalStateId = $publication->getAnimalState()->getId();
        
        if (null === $image) {
            throw new NotFoundHttpException("L'image d'id ".$imageId. " n'existe pas.");
        }
        $publication->removeImage($image);
        $em->persist($publication);
        $em->flush();
        
        $request->getSession()->getFlashBag()->add('info', 'Image retirée de la publication !');
        
        return $this->redirectToRoute('admin_animal_publication_card', array(
            'state' => $state, 
            'animalStateId' => $animalStateId, 
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
        
        if ($state == 'perdu' || $state == 'trouvé') {
            $form = $this->get('form.factory')->create(PublicationOnAnimalWithPlaceType::class, $publication);
        } else {
            $form = $this->get('form.factory')->create(PublicationOnAnimalType::class, $publication);
        }

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
     * (De)Publish the publication
     *
     * @Route("/admin/animal/{publicationId}/publier/{published}", name="admin_animal_publication_publish", requirements={"publicationId": "\d+", "published": "on|off"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function publishAction($publicationId, $published, Request $request) {
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('AppBundle:Publication')->findOneBy(array('id' => $publicationId));
        
        switch($published) {
            case 'on':
                $published = true;
                break;
            case 'off':
                $published = false;
                break;
        }
        
        $publication->setPublished($published);
        $em->persist($publication);
        $em->flush();
        
        $request->getSession()->getFlashBag()->add('info', 'Publication bien mise à jour !');
        
        $animalStateId = $publication->getAnimalState()->getId();
        $state = $publication->getAnimalState()->getState()->getType();
        
        return $this->redirectToRoute('admin_animal_publication_card', array('state' => $state, 'animalStateId'=> $animalStateId, 'publicationId' => $publicationId));
    }

    /**
     * Edit a publication on animal 
     *
     * @Route("/admin/animal/{state}/{animalStateId}/publication/{publicationId}/edit", name="admin_animal_publication_edit", requirements={"animalStateId": "\d+", "publicationId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($state, $animalStateId, $publicationId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $animalState = $em->getRepository('AppBundle:AnimalState')->findOneById($animalStateId);
        $publication = $em->getRepository('AppBundle:Publication')->findOneById($publicationId);
        $animal = $publication->getAnimalState()->getAnimal();
        
        if (null === $publication) {
            throw new NotFoundHttpException("La publication d'id ".$publicationId. " que vous recherchez n'existe pas.");
        }
        
        if ($state == 'perdu' || $state == 'trouvé') {
            $form = $this->get('form.factory')->create(PublicationOnAnimalWithPlaceType::class, $publication);
        } else {
            $form = $this->get('form.factory')->create(PublicationOnAnimalType::class, $publication);
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
               
            $em->persist($publication);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Publication bien éditée !');

            return $this->redirectToRoute('admin_animal_publication_card', array('state' => $state, 'animalStateId' => $animalStateId, 'publicationId' => $publication->getId()));
        }

        return $this->render('admin/publication/animal/edit.html.twig', array(
            'publication' => $publication,
            'form' => $form->createView()
        ));
    }

    /**
     * Delete a Animal
     *
     * @Route("/admin/animal/publication/{id}/supprimer", name="admin_animal_publication_delete", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $publication = $em->getRepository('AppBundle:Publication')->find($id);

        if (null === $publication) {
            throw new NotFoundHttpException("La publication d'id ".$id. " que vous souhaitez supprimer n'existe pas.");
        }

        $em->remove($publication);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "La publication a bien été supprimée !");

        return $this->redirectToRoute('admin_home');

    }

}
