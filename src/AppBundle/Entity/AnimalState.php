<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * AnimalState
 *
 * @ORM\Table(name="animal_state")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnimalStateRepository")
 */
class AnimalState
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\DateTime( 
     * message="Date non valide"     
     * )
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Animal", inversedBy="animalStates", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $animal;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\State", inversedBy="animalStates", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Valid()
     */
    private $state;
    
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Publication", inversedBy="animalState", cascade={"persist", "remove"})
     * @Assert\Valid()
     * @ORM\joinColumn(onDelete="SET NULL")
     */
    private $publication;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $currentState;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return AnimalState
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set animal
     *
     * @param \AppBundle\Entity\Animal $animal
     *
     * @return AnimalState
     */
    public function setAnimal(\AppBundle\Entity\Animal $animal)
    {
        $this->animal = $animal;
        return $this;
    }

    /**
     * Get animal
     *
     * @return \AppBundle\Entity\Animal
     */
    public function getAnimal()
    {
        return $this->animal;
    }

    /**
     * Set state
     *
     * @param \AppBundle\Entity\State $state
     *
     * @return AnimalState
     */
    public function setState(\AppBundle\Entity\State $state)
    {
        $this->state = $state;

        return $this;

    }

    /**
     * Get state
     *
     * @return \AppBundle\Entity\State
     */
    public function getState()
    {
        return $this->state;
    }


    /**
     * Set publication
     *
     * @param \AppBundle\Entity\Publication $publication
     *
     * @return AnimalState
     */
    public function setPublication(\AppBundle\Entity\Publication $publication = null)
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * Get publication
     *
     * @return \AppBundle\Entity\Publication
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * Set currentState
     *
     * @param boolean $currentState
     *
     * @return AnimalState
     */
    public function setCurrentState($currentState)
    {
        $this->currentState = $currentState;

        return $this;
    }

    /**
     * Get currentState
     *
     * @return boolean
     */
    public function getCurrentState()
    {
        return $this->currentState;
    }
}
