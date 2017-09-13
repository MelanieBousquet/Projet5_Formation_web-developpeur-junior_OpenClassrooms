<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Animal;
use AppBundle\Entity\Note;
use AppBundle\Entity\Publication;
use AppBundle\Entity\Breed;
use AppBundle\Entity\Sex;
use AppBundle\Entity\State;
use AppBundle\Entity\AnimalState;
use AppBundle\Entity\Type;
use AppBundle\Entity\TypeIdentification;
use AppBundle\Form\AnimalType;
use AppBundle\Form\AnimalEditType;
use AppBundle\Form\NoteType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;


class AnimalController extends Controller
{
    /**
    * List of animals
    *
    * @Route("/admin/{animalType}/{state}", name="admin_animal")
    */
    public function viewListAction($animalType, $state, Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Animal');

        if ($state == 'adoptable') {
            $listAnimals = $repository->animalsToAdoptList($animalType) ;
        } else if ($state == 'reserve') {
            $listAnimals = $repository->animalsAlmostAdoptedList($animalType) ;
        } else {
            $listAnimals = $repository->animalsNotToAdoptList($animalType, $state) ;
        }
        
        $haveMainImage = array();
        foreach ($listAnimals as $animal) {
            $animalId = $animal->getId();
            foreach($animal->getImages() as $image) {
                if ($image->getMain()) {                    
                    array_push($haveMainImage, $animalId);
                }
            }
        }

        return $this->render('admin/animal/viewList.html.twig', array(
            'listAnimals' => $listAnimals,
            'haveMainImage' => $haveMainImage
        ));
    }
    
    /**
    * View a specific animal
    *
    * @Route("/admin/animal/{id}/fiche", name="admin_animal_card", requirements={"id": "\d+"})
    */
    public function viewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $animal = $em->getRepository('AppBundle:Animal')->findOneById($id);
        $mainImage = $em->getRepository('AppBundle:Image')->findMainImageByAnimal($id);
        
        $note = new Note();
        $form = $this->get('form.factory')->create(NoteType::class, $note);
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $note->setUser($user);
        $note->setAnimal($animal);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($note);
            $em->flush();
            
            $request->getSession()->getFlashBag()->add('info', 'Votre note a bien été ajoutée');
            
            $this->redirectToRoute('admin_animal_card', array('id' => $animal->getId()));
        }

        return $this->render('admin/animal/view.html.twig', array(
            'animal' => $animal,
            'mainImage' => $mainImage,
            'form' => $form->createView()
        ));
    }

    /**
    * Add a animalToAdopt
    *
    * @Route("/admin/animaux-ajout", name="admin_animal_add")
    */
    public function addAction(Request $request)
    {

        $animal = new Animal();
        $form = $this->get('form.factory')->create(AnimalType::class, $animal);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            
            if ($animal->getAnimalStates()) {
                foreach($animal->getAnimalStates() as $animalState) {
                    $animalState->setAnimal($animal);
                }
            }

            if ($animal->getImages()) {
                foreach($animal->getImages() as $image)
                {
                   $image->setAnimal($animal);
                }
            }
            
            $em->persist($animal);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Animal bien enregistré');

            return $this->redirectToRoute('admin_animal_card', array('id' => $animal->getId()));
        }

        return $this->render('admin/animal/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    * Edit a animal
    *
    * @Route("/admin/animaux/{id}/edit", name="admin_animal_edit", requirements={"id": "\d+"})
    */
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
    */
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

}
