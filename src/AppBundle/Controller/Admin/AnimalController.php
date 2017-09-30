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
use AppBundle\Form\AnimalStateAddType;
use AppBundle\Form\NoteType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;


class AnimalController extends Controller
{
    /**
     * List of animals, depending on the last state (currentState : true)
     *
     * @Route("/admin/liste/{animalType}/{state}", name="admin_animal")
     */
    public function viewListAction($animalType, $state, Request $request)
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Animal')
        ;

        $listAnimals = $repository->animalsListDependingOnLastState($animalType, $state) ;

        return $this->render('admin/animal/viewList.html.twig', array(
            'listAnimals' => $listAnimals
        ));
    }
    
    /**
     * View a specific animal and notes
     *
     * @Route("/admin/animal/{id}/fiche", name="admin_animal_card", requirements={"id": "\d+"})
     */
    public function viewAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $animal = $em->getRepository('AppBundle:Animal')->findOneById($id);
        $listNotes = $em->getRepository('AppBundle:Note')->findNotesByObject('animal', $id);
        
        $note = new Note();
        $form = $this->get('form.factory')->create(NoteType::class, $note);
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $note->setUser($user);
        $note->setAnimal($animal);
        
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($note);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Votre note a bien été ajoutée !');
            return $this->redirectToRoute('admin_animal_card', array('id' => $animal->getId()));
        }

        return $this->render('admin/animal/view.html.twig', array(
            'animal' => $animal,
            'form' => $form->createView(),
            'listNotes'  => $listNotes
        ));
    }

    /**
     * Add an animal
     *
     * @Route("/admin/animal-ajout", name="admin_animal_add")
     * @Security("has_role('ROLE_ADMIN')")
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
                    // Set all currentState to false, before finding the 'new' last state depending on the date
                    $animalState->setCurrentState(false);
                }
            }

            if ($animal->getImages()) {
                foreach($animal->getImages() as $image) { $image->setAnimal($animal); }
            } 
            
            $em->persist($animal);
            $em->flush();
            
            // Then search the 'new' last state depending on the date and set currentState to true
            $currentAnimalState = $em->getRepository('AppBundle:AnimalState')->findLastState($animal->getId());
            $currentAnimalState->setCurrentState(true);
            
            $em->persist($currentAnimalState);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Animal bien enregistré ! Sur cette page, vous pouvez gérer les photos, définir une photo principale, publier sur un statut spécifique..');

            return $this->redirectToRoute('admin_animal_card', array('id' => $animal->getId()));
        }

        return $this->render('admin/animal/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * Edit an animal
     *
     * @Route("/admin/animal/{id}/edit", name="admin_animal_edit", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function editAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoAnimal = $em->getRepository('AppBundle:Animal');
        $repoAnimalState = $em->getRepository('AppBundle:AnimalState');
        $animal = $repoAnimal->find($id);

        $form = $this->get('form.factory')->create(AnimalEditType::class, $animal);

        if (null === $animal) {
            throw new NotFoundHttpException("L'animal d'id ".$id. " n'existe pas.");
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->persist($animal);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Animal bien édité');

            return $this->redirectToRoute('admin_animal_card', array('id' => $animal->getId()));
        }

        return $this->render('admin/animal/edit.html.twig', array(
            'animal' => $animal,
            'form' => $form->createView(),
        ));
    }

    /**
     * Delete an Animal
     *
     * @Route("/admin/animal/{id}/supprimer", name="admin_animal_delete", requirements={"id": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $animal = $em->getRepository('AppBundle:Animal')->find($id);

        if (null === $animal) {
            throw new NotFoundHttpException("L'animal d'id ".$id. " n'existe pas.");
        }

        $em->remove($animal);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "L'animal a bien été supprimé !");

        return $this->redirectToRoute('admin_home');

    } 
    
    /** 
     * Add an animalState
     *
     * @Route("/admin/animal/{animalId}/statut/ajout", name="admin_animal_state_add", requirements={"animalId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function addAnimalStateAction($animalId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoAnimal = $em->getRepository('AppBundle:Animal');
        $repoAnimalState = $em->getRepository('AppBundle:AnimalState');
        $animal = $repoAnimal->find($animalId);
        $animalState = new AnimalState();
        
        $form = $this->get('form.factory')->create(AnimalStateAddType::class, $animalState);

        if (null === $animal) {
            throw new NotFoundHttpException("L'animal d'id ".$animalId. " n'existe pas, vous ne pouvez pas créer de nouveau statut.");
        }

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $animal->addAnimalState($animalState);
                   
            foreach($animal->getAnimalStates() as $anState) {
                $anState->setCurrentState(false);
            } 
            
            $em->persist($animal);
            $em->flush();
            
            $currentAnimalState = $repoAnimalState->findLastState($animal->getId());
            $currentAnimalState->setCurrentState(true);
            $em->persist($currentAnimalState);
            $em->flush();

            $request->getSession()->getFlashBag()->add('info', 'Statut bien ajouté');

            return $this->redirectToRoute('admin_animal_card', array('id' => $animalId));

        }

        return $this->render('admin/animal/addState.html.twig', array(
            'animal' => $animal,
            'animalState' => $animalState,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * Delete an AnimalState
     *
     * @Route("/admin/animal/{animalId}/{animalStateId}/supprimer", name="admin_animal_state_delete", requirements={"animalId": "\d+", "animalStateId": "\d+"})
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAnimalStateAction($animalId, $animalStateId, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoAnimalState = $em->getRepository('AppBundle:AnimalState');
        $animal = $em->getRepository('AppBundle:Animal')->find($animalId);
        
        $animalState = $repoAnimalState->find($animalStateId);

        if (null === $animalState) {
            throw new NotFoundHttpException("Le statut d'id ".$animalStateId. " pour cet animal n'existe pas.");
        }
        if (count($animal->getAnimalStates()) == 1) {
            throw new NotFoundHttpException("Vous ne pouvez pas supprimer le seul statut de cet animal");
        }
        
        $animal = $animalState->getAnimal();
        $animal->removeAnimalState($animalState);
        
        foreach($animal->getAnimalStates() as $anState) {
                $anState->setCurrentState(false);
        } 
            
        $em->persist($animal);
        $em->remove($animalState);
        $em->flush();
        
        $currentAnimalState = $repoAnimalState->findLastState($animal->getId());
        $currentAnimalState->setCurrentState(true);
        $em->persist($currentAnimalState);
        $em->flush();

        $request->getSession()->getFlashBag()->add('info', "Le statut a bien été supprimé !");

        return $this->redirectToRoute('admin_animal_card', array('id' => $animalState->getAnimal()->getId()));

    }

}
