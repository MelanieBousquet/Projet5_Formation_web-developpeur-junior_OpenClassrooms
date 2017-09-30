<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Animal;

class AnimalListener
{

    public function prePersist(Animal $animal, LifecycleEventArgs $args)
    {
        $this->updateCurrentState($animal, $args);
        dump($currentAnimalState);
    }
    
    public function preUpdate(Animal $animal, LifecycleEventArgs $args) 
    {
        $this->updateCurrentState($animal, $args);
        dump($currentAnimalState);
    }
    
    public function updateCurrentState(animal $animal, LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager(); 
        $repository = $em->getRepository('Animal');
        $animal = $repository->find($animal->getId());
        
    }

}