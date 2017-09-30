<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sex
 *
 * @ORM\Table(name="sex")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SexRepository")
 */
class Sex
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
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Animal", mappedBy="sex")
     * @Assert\Valid()
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
     * Set type
     *
     * @param string $type
     *
     * @return Sex
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

    public function addAnimal(Animal $animal)
    {
        $this->animals[] = $animal;
        $animal->setSex($this);
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

