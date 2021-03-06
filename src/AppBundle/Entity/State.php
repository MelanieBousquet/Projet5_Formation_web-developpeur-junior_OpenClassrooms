<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * State
 *
 * @ORM\Table(name="state")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StateRepository")
 * @UniqueEntity(fields="type", message="Ce statut existe déjà")
 */
class State
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
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, unique=true)
     * @Assert\NotBlank(
     * message = "Ce champ ne doit pas être vide"
     * )
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AnimalState", mappedBy="state")
     * @Assert\Valid()
     */
    private $animalStates;

    public function __construct()
    {
        $this->animalStates = new ArrayCollection();
    }

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
     * Set type
     *
     * @param string $type
     *
     * @return State
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function addAnimalState(AnimalState $animalState)
    {
        $this->animalStates[] = $animalState;
        $animalState->setState($this);
        return $this;

    }

    public function removeAnimalState(AnimalState $animalState)
    {
        $this->animalStates->removeElement($animalState);
    }

    public function getAnimalStates()
    {
        return $this->animalStates;
    }
}

