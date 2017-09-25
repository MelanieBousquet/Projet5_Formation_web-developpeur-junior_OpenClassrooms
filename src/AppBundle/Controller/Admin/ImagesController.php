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
    public function viewListAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();        
        $animal = $em->getRepository('AppBundle:Animal')->findOneBy(array('id' => $id));
        
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
     * Define the main image of a specific animal
     *
     * @Route("/admin/{object}/{objectId}/photo-principale/{imageId}", name="admin_object_mainimage", requirements={"object": "animal|publication|evenement", "objectId": "\d+", "imageId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function changeMainImageAction($object, $objectId, $imageId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $objectNameRepo = 'AppBundle:' . ucfirst($object);
        $objectById = $em->getRepository($objectNameRepo)->find($objectId);
        $imageToDefineAsMain = $em->getRepository('AppBundle:Image')->find($imageId);

        if (null === $imageToDefineAsMain) {
            throw new NotFoundHttpException("L'image d'id ".id. " n'existe pas.");
        }
            
        $objectById->setMainImage($imageToDefineAsMain);
        $em->persist($objectById);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "L'image principale a bien été modifiée !");
        
        switch($object) {
            case 'animal' :
                $route = 'admin_animal_images';
                $attr = array('id' => $objectId);
                break;
            case 'publication' :
                $route = 'admin_animal_publication_card';
                $attr = array('state' => $objectById->getAnimalState()->getState()->getType(), 'animalStateId' => $objectById->getAnimalState()->getId(), 'publicationId' => $objectId );
                break;
            case 'evenement' :
                $route = 'admin_event_card';
                $attr = array('id' => $objectId);
                break;
        }
            
        return $this->redirectToRoute($route, $attr);
    }
    
    
    /**
    * Delete an Image 
    *
    * @Route("/admin/{object}/{objectId}/image/{imageId}/supprimer", name="admin_image_delete", requirements={"object": "animal|publication|evenement", "objectId": "\d+", "imageId": "\d+"})
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function deleteAction($object, $objectId, $imageId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('AppBundle:Image')->find($imageId);
        

        if (null === $image) {
            throw new NotFoundHttpException("L'image d'id ".id. " n'existe pas.");
        }
        
        switch($object) {
            case 'animal' :
                $route = 'admin_animal_images';
                $attr = array('id' => $objectId);
                $em->remove($image);
                break;
            case 'publication' :
                $publication = $em->getRepository('AppBundle:Publication')->findOneById($objectId);
                $route = 'admin_animal_publication_card';
                $attr = array('state' => $publication->getAnimalState()->getState()->getType(), 'animalStateId' => $publication->getAnimalState()->getId(), 'publicationId' => $objectId );
                $publication->removeImage($image);
                $em->persist($publication);
                break;
            case 'evenement' :
                $route = 'admin_event_card';
                $attr = array('id' => $objectId);
                $em->remove($image);
                break;
        }
        
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "L'image a bien été supprimée.");
        return $this->redirectToRoute($route, $attr);

    }

}
