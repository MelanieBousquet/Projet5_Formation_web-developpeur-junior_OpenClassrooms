<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * TypeIdentification
 *
 * @ORM\Table(name="type_identification")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TypeIdentificationRepository")
 * @UniqueEntity(fields="name", message="Ce type d'identification existe déjà")
 */
class TypeIdentification
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Animal", mappedBy="typeIdentification")
     */
    private $animals;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return TypeIdentification
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function addAnimal(Animal $animal)
    {
        $this->animals[] = $animal;
        $animal->setTypeIdentification($this);
        return $this;
    }

    public function removeAnimal(Animal $animal)
    {
        $this->animals->removeElement($animal);
    }

    public function getAnimals()
    {
        return $this->animals;
    }
}

