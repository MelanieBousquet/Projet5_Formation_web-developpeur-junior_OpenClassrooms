<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Animal;
use AppBundle\Entity\Image;
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


class ImagesController extends Controller
{
    /**
     * List of images of a specific animal
     *
     * @Route("/admin/animal/{id}/photos", name="admin_animal_images", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_FA')")
     */
    public function viewListForAnimalAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();        
        $animal = $em->getRepository('AppBundle:Animal')->findOneBy(array('id' => $id));
        
        if (null === $animal) {
            throw new NotFoundHttpException("L'animal' d'id ".$id. " n'existe pas.");
        }
        
        $image = new Image();
        $form = $this->get('form.factory')->create(ObjectImageType::class, $image);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $image->setAnimal($animal);
            $em->persist($image);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Image bien enregistrée ! ');

            return $this->redirectToRoute('admin_animal_images', array('id' => $animal->getId()));
        }

        return $this->render('admin/animal/images/viewList.html.twig', array(
            'animal' => $animal, 
            'form' => $form->createView()
        ));
    }    
    
    /**
     * List of images of a specific publication
     *
     * @Route("/admin/publication/{id}/photos", name="admin_publication_images", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_FA')")
     */
    public function viewListForPublicationAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();        
        $publication = $em->getRepository('AppBundle:Publication')->findOneBy(array('id' => $id));
        
        if (null === $publication) {
            throw new NotFoundHttpException("La publication d'id ".$id. " n'existe pas.");
        }
        
        $image = new Image();
        $form = $this->get('form.factory')->create(ObjectImageType::class, $image);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $image->addPublication($publication);
            $em->persist($image);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Image bien enregistrée ! ');

            return $this->redirectToRoute('admin_publication_images', array('id' => $publication->getId()));
        }

        return $this->render('admin/publication/default/images/viewList.html.twig', array(
            'publication' => $publication, 
            'form' => $form->createView()
        ));
    }    
    
    /**
     * List of images of a specific event
     *
     * @Route("/admin/evenement/{id}/photos", name="admin_event_images", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_FA')")
     */
    public function viewListForEvenementAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();        
        $event = $em->getRepository('AppBundle:Publication')->findOneBy(array('id' => $id));
        
        if (null === $event) {
            throw new NotFoundHttpException("L'evenement' d'id ".$id. " n'existe pas.");
        }
        
        $image = new Image();
        $form = $this->get('form.factory')->create(ObjectImageType::class, $image);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $image->addPublication($event);
            $em->persist($image);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Image bien enregistrée ! ');

            return $this->redirectToRoute('admin_event_images', array('id' => $event->getId()));
        }

        return $this->render('admin/publication/events/images/viewList.html.twig', array(
            'event' => $event, 
            'form' => $form->createView()
        ));
    }
    
    
    /**
     * Define the main image of a specific animal
     *
     * @Route("/admin/{object}/{objectId}/photo-principale/{imageId}", name="admin_object_mainimage", requirements={"object": "animal|publication|event", "objectId": "\d+", "imageId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function changeMainImageAction($object, $objectId, $imageId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($object == 'event' || $object == 'publication') {
            $objectNameRepo = 'AppBundle:Publication';
        } else {
            $objectNameRepo = 'AppBundle:Animal' ;
        }
        
        $objectById = $em->getRepository($objectNameRepo)->find($objectId);
        $imageToDefineAsMain = $em->getRepository('AppBundle:Image')->find($imageId);

        if (null === $imageToDefineAsMain) {
            throw new NotFoundHttpException("L'image d'id ".$id. " n'existe pas.");
        }
            
        $objectById->setMainImage($imageToDefineAsMain);
        $em->persist($objectById);
        $em->flush();
        
        $request->getSession()->getFlashBag()->add('info', "L'image principale a bien été modifiée !");
        
        // Depending on the object of the images, define the route and its attributes
        switch($object) {
            case 'animal' :
                $route = 'admin_animal_card';
                $attr =  array('id' => $objectId);
                break;
            case 'publication' :
                if ($objectById->getAnimalState() == true) {
                    $route = 'admin_animal_publication_card';
                    $state = $objectById->getAnimalState()->getState()->getType() ;
                    $animalStateId = $objectById->getAnimalState()->getId();
                    $attr =  array('state' => $state, 'animalStateId' => $animalStateId,'publicationId' => $objectId) ;
                } else {
                    $route = 'admin_publication_images';
                    $attr = array('id' => $objectId);
                }
                break;
            case 'event' :
                $route = 'admin_event_images';
                $attr = array('id' => $objectId);
                break;
        }
        return $this->redirectToRoute($route, $attr);
    }
    
    
    /**
     * Delete an Image 
     *
     * @Route("/admin/{object}/{objectId}/image/{imageId}/supprimer", name="admin_image_delete", requirements={"object":  "animal|publication|event", "objectId": "\d+", "imageId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($object, $objectId, $imageId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('AppBundle:Image')->find($imageId);
        

        if (null === $image) {
            throw new NotFoundHttpException("L'image d'id ".$imageId. " que vous souhaitez supprimer n'existe pas.");
        }
        
        // Depending on the object of the images, define the route and its attributes
        switch($object) {
            case 'animal' :
                $em->remove($image);
                $route = 'admin_animal_publication_card';
                $attr =  array('id' => $objectId);
                break;
            case 'publication' :
                $publication = $em->getRepository('AppBundle:Publication')->findOneById($objectId);
                $publication->removeImage($image);
                $em->persist($publication);
                 if ($objectById->getAnimalState() == true) {
                    $route = 'admin_animal_publication_card';
                    $state = $objectById->getAnimalState()->getState()->getType() ;
                    $animalStateId = $objectById->getAnimalState()->getId();
                    $attr =  array('state' => $state, 'animalStateId' => $animalStateId,'publicationId' => $objectId) ;
                } else {
                    $route = 'admin_publication_images';
                    $attr = array('id' => $objectId);
                }
                break;
            case 'event' :
                $em->remove($image);
                $route = 'admin_event_images';
                break;
        }
        
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "L'image a bien été supprimée.");
        
        return $this->redirectToRoute($route, $attr);

    }

}
