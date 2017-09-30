<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Type
 *
 * @ORM\Table(name="type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TypeRepository")
 */
class Type
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
     * @Assert\NotBlank(
     * message = "Ce champ ne doit pas être vide"
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Breed", mappedBy="type")
     * @Assert\Valid()
     */
    private $breeds;

    public function __construct()
    {
        $this->brands = new ArrayCollection();
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
     * @return Type
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

    public function addBreed(Breed $breed)
    {
        $this->breeds[] = $breed;
        $breed->setType($this);
        return $this;
    }

    public function removeBreed(Breed $breed)
    {
        $this->breeds->removeElement($breed);
    }

    public function getBreeds()
    {
        return $this->breeds;
    }
}

