<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Publication;
use AppBundle\Entity\Animal;
use AppBundle\Entity\Type;
use AppBundle\Entity\Breed;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Date;

class AdoptionAnimalController extends Controller
{
    /**
     * List of animals waiting for adoption, by default or depending on user request
     *
     * @Route("/adoption/{animalType}/sexe-{sex}/race-{breed}/age-{age}", name="front_list_animal_adoption", defaults={"sex": "all", "breed": "all", "age": "all"}, requirements={"animalType": "chien|chat", "sex": "femelle|mâle|all", "age":"chiot/chaton/adulte"})
     */
    public function viewListOnAdoptionAction($animalType, $sex, $breed, $age, Request $request)
    {
        $em = $this->getDoctrine()->getManager() ;
        $repoPublication = $em->getRepository('AppBundle:Publication');
        // Search specifics animals depending on user request
        $publicationsOnAnimalsToAdopt = $repoPublication->findPublicationsOnAnimalsToAdopt($animalType, $sex, $breed, $age) ;
        
        // Find $breeds depending on animalType for the form   
        $repoType = $em->getRepository('AppBundle:Type');
        $type = $repoType->findOneBy(array('name' => $animalType));
        $breeds = $type->getBreeds();
        // 'Baby' adapted vocabulary for the form, depending on animalType 
        $animalType == 'chien' ? $baby = 'chiot (<1an)' : $baby = 'chaton (<1an)' ;
        
        // Create the form for searching animals with specifics criteria 
        $form = $this->createFormBuilder()
            ->add('sex', ChoiceType::class, array('choices' => array('tous' => 'all', 'mâle' => 'mâle', 'femelle' => 'femelle')))
            ->add('breed', EntityType::class, array('class' => 'AppBundle:Breed', 'choice_label' => 'name', 'choices' => $breeds, 'placeholder' => 'tous', 'required' => false))
            ->add('age', ChoiceType::class, array('choices' => array('tous' => 'all', $baby => $baby , 'adulte (>1an)' => 'adulte')))
            ->add('Chercher', SubmitType::class)
            ->getForm();
        ;
        // Handle the submitted form 
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $data = $form->getData();
            $data['breed'] ? $breed = $data['breed']->getName() : $breed = 'all' ;
            // Recharge the page with the chosen criteria
            return $this->redirectToRoute('front_list_animal_adoption', array('animalType' => $animalType, 'sex' => $data['sex'], 'breed' => $breed, 'age' => $data['age']));
        }

        return $this->render('front/animal/adoption/viewListOnAdoption.html.twig', array(
            'publicationsOnAnimalsToAdopt' => $publicationsOnAnimalsToAdopt,
            'form' => $form->createView()
        ));
    }
    
    /**
     * View an animal waiting for adoption, or already adopted
     *
     * @Route("/adoption/animal/{id}/fiche", name="front_adoption_animal_card", requirements={"id": "\d+"})
     */
     
    public function viewAnimalAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $publication = $em->getRepository('AppBundle:Publication')->findOneById($id);
        
        return $this->render('front/animal/adoption/viewAnimal.html.twig', array(
            'publication' => $publication
        ));
    }
    
    /**
     * View the list of the adopted animals of a specific year 
     *
     * @Route("/adoption/animaux/liste-des-adoptes/{year}", name="front_list_animal_adopted", defaults={"year": "derniere-annee"}, requirements={"year": "derniere-annee|\d+"})
     */
    public function viewListAdoptedAction($year, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repoAnimal = $em->getRepository('AppBundle:Animal');
        
        // In order to generate the links of the differents pages of adopted animals depending on the years of the first and last adoption
        $yearOfTheFirstAdoption = $repoAnimal->getTheYearOfTheFirstAdoption();
        $yearOfTheLastAdoption = $repoAnimal->getTheYearOfTheLastAdoption();
        
        // By default, show the last animals adopted
        if ($year == 'derniere-annee') { $year = $yearOfTheLastAdoption[1]; } 
        
        $listAdoptedAnimals = $repoAnimal->getAdoptedAnimalsByYear($year);
        
        return $this->render('front/animal/adoption/viewListAdopted.html.twig', array(
            'year' => $year,
            'listAdoptedAnimals' => $listAdoptedAnimals,
            'yearOfTheFirstAdoption' => $yearOfTheFirstAdoption,
            'yearOfTheLastAdoption' => $yearOfTheLastAdoption
        ));
    }
}