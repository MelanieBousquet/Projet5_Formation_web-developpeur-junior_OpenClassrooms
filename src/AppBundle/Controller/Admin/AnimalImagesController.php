<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Animal;
use AppBundle\Entity\Image;
use AppBundle\Form\AnimalImageType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;


class AnimalImagesController extends Controller
{
    /**
    * List of images of a specific animal
    *
    * @Route("/admin/animal/{id}/photos", name="admin_animal_images", requirements={"id": "\d+"})
    */
    public function viewListAction($id, Request $request)
    {
        $em = $this
            ->getDoctrine()
            ->getManager();
        $imgRepository = $em->getRepository('AppBundle:Image');
        $animalRepository = $em->getRepository('AppBundle:Animal');
        
        $listImages = $imgRepository->findImagesByAnimal($id);
        $mainImage = $imgRepository->findMainImageByAnimal($id);
        $animal = $animalRepository->findOneBy(array('id' => $id));
        
        $image = new Image();
        $form = $this->get('form.factory')->create(AnimalImageType::class, $image);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $image->setAnimal($animal);
            $em->persist($image);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Image bien enregistrée ! ');

            return $this->redirectToRoute('admin_animal_images', array('id' => $animal->getId()));
        }

        return $this->render('admin/animal/images/viewList.html.twig', array(
            'listImages' => $listImages,
            'mainImage' =>$mainImage,
            'animal' => $animal, 
            'form' => $form->createView()
        ));
    }
    
    
    /**
     * Define the main image of a specific animal
     *
     * @Route("/admin/animal/{animalId}/photo-principale/{imageId}", name="admin_animal_mainimage", requirements={"animalId": "\d+", "imageId": "\d+"})
     */
    public function changeMainImageAction($animalId, $imageId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $animal = $em->getRepository('AppBundle:Animal')->find($animalId);
        $imageToDefineAsMain = $em->getRepository('AppBundle:Image')->find($imageId);

        if (null === $imageToDefineAsMain) {
            throw new NotFoundHttpException("L'image d'id ".id. " n'existe pas.");
        }
        
        foreach($animal->getImages() as $image) {
            $image->setMain(false);
        }
        $imageToDefineAsMain->setMain(true);
        $em->persist($image);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "L'image principale a bien été modifiée !");

        return $this->redirectToRoute('admin_animal_images', array('id' => $animalId));
    }
    
    
    /**
    * Delete an Image 
    *
    * @Route("/admin/animal/{animalId}image/{imageId}/supprimer", name="admin_image_delete", requirements={"animalId": "\d+", "imageId": "\d+"})
    */
    public function deleteAction($animalId, $imageId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $image = $em->getRepository('AppBundle:Image')->find($imageId);

        if (null === $image) {
            throw new NotFoundHttpException("L'image d'id ".id. " n'existe pas.");
        }
        
        $em->remove($image);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "L'image a bien été supprimée.");

        return $this->redirectToRoute('admin_animal_images', array('id' => $animalId));

    }

}
