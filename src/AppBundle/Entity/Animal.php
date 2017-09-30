<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Animal
 *
 * @ORM\Table(name="animal")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AnimalRepository")
 */
class Animal
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    /**
     * @var date
     *
     * @ORM\Column(name="birthday", type="date", nullable=true)
     */
    private $birthday;

    /**
     * @var bool
     *
     * @ORM\Column(name="sterilised", type="boolean", nullable=true)
     */
    private $sterilised;

    /**
     * @var string
     *
     * @ORM\Column(name="identificationNumber", type="string", length=255, nullable=true, unique=true, nullable=true)
     */
    private $identificationNumber;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Sex", inversedBy="animals", cascade={"persist"})
     * @Assert\Valid()
     */
    private $sex;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Breed", inversedBy="animals", cascade={"persist"})
     *
     */
    private $breed;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TypeIdentification", inversedBy="animals", cascade={"persist"})
     * @Assert\Valid()
     */
    private $typeIdentification;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="animals", cascade={"persist"})
     * @Assert\Valid()
     */
    private $user;
    
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $mainImage;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="animal", cascade={"persist", "remove"})
     * @Assert\Valid()
     * @ORM\JoinColumn(nullable=true)
     */
    private $images;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\AnimalState", mappedBy="animal",cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $animalStates;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;

    
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Note", mappedBy="animal", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $notes;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->animalStates = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->notes = new ArrayCollection();
    }
    

    /**
     * Get id
     *
     * @return integer
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
     * @return Animal
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


    /**
     * Set sterilised
     *
     * @param boolean $sterilised
     *
     * @return Animal
     */
    public function setSterilised($sterilised)
    {
        $this->sterilised = $sterilised;

        return $this;
    }

    /**
     * Get sterilised
     *
     * @return boolean
     */
    public function getSterilised()
    {
        return $this->sterilised;
    }

    /**
     * Set identificationNumber
     *
     * @param string $identificationNumber
     *
     * @return Animal
     */
    public function setIdentificationNumber($identificationNumber)
    {
        $this->identificationNumber = $identificationNumber;

        return $this;
    }

    /**
     * Get identificationNumber
     *
     * @return string
     */
    public function getIdentificationNumber()
    {
        return $this->identificationNumber;
    }


    /**
     * Set typeIdentification
     *
     * @param \AppBundle\Entity\TypeIdentification $typeIdentification
     *
     * @return Animal
     */
    public function setTypeIdentification(\AppBundle\Entity\TypeIdentification $typeIdentification = null)
    {
        $this->typeIdentification = $typeIdentification;

        return $this;
    }

    /**
     * Get typeIdentification
     *
     * @return \AppBundle\Entity\TypeIdentification
     */
    public function getTypeIdentification()
    {
        return $this->typeIdentification;
    }


    /**
     * Add image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Animal
     */
    public function addImage(\AppBundle\Entity\Image $image)
    {
        $this->images[] = $image;
        $image->setAnimal($this);

        return $this;
    }

    /**
     * Remove image
     *
     * @param \AppBundle\Entity\Image $image
     */
    public function removeImage(\AppBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add animalState
     *
     * @param \AppBundle\Entity\AnimalState $animalState
     *
     * @return Animal
     */
    public function addAnimalState(\AppBundle\Entity\AnimalState $animalState)
    {
        $this->animalStates[] = $animalState;
        $animalState->setAnimal($this);

        return $this;
    }

    /**
     * Remove animalState
     *
     * @param \AppBundle\Entity\AnimalState $animalState
     */
    public function removeAnimalState(\AppBundle\Entity\AnimalState $animalState)
    {
        $this->animalStates->removeElement($animalState);
    }

    /**
     * Get animalStates
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAnimalStates()
    {
        return $this->animalStates;
    }


    /**
     * Set sex
     *
     * @param \AppBundle\Entity\Sex $sex
     *
     * @return Animal
     */
    public function setSex(\AppBundle\Entity\Sex $sex = null)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return \AppBundle\Entity\Sex
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set breed
     *
     * @param \AppBundle\Entity\Breed $breed
     *
     * @return Animal
     */
    public function setBreed(\AppBundle\Entity\Breed $breed = null)
    {
        $this->breed = $breed;

        return $this;
    }

    /**
     * Get breed
     *
     * @return \AppBundle\Entity\Breed
     */
    public function getBreed()
    {
        return $this->breed;
    }
    
    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Animal
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }


    /**
     * Set birthday
     *
     * @param \DateTime $birthday
     *
     * @return Animal
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }
    
    /**
     * Age calcul with $birthday
     */
    public function getAge()
    {
        if ($this->birthday) {
            $dateInterval = $this->birthday->diff(new \DateTime());
            $years = $dateInterval->y;
            $months = $dateInterval->m;
            $days = $dateInterval->d;
            
            switch ($years) {
                case 1 :
                    $age = $years . ' an';
                    if ($months !== 0) {
                        $age .= ' et ' . $months . ' mois' ;
                    }
                    break;
                case 0 : 
                    $age = $months . ' mois' ;
                    if ($days >= 15) {
                        $age .= ' et 1/2' ;
                    }
                    if ($months == 0) {
                        $age = $days . ' jours' ;
                    }
                    break;
                default:
                    $age = $years . ' ans' ;
            }
            
           return $age ;
        }
        
        
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Animal
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set mainImage
     *
     * @param \AppBundle\Entity\Image $mainImage
     *
     * @return Animal
     */
    public function setMainImage(\AppBundle\Entity\Image $mainImage = null)
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * Get mainImage
     *
     * @return \AppBundle\Entity\Image
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }

    /**
     * Add note
     *
     * @param \AppBundle\Entity\Note $note
     *
     * @return Animal
     */
    public function addNote(\AppBundle\Entity\Note $note)
    {
        $this->notes[] = $note;

        return $this;
    }

    /**
     * Remove note
     *
     * @param \AppBundle\Entity\Note $note
     */
    public function removeNote(\AppBundle\Entity\Note $note)
    {
        $this->notes->removeElement($note);
    }

    /**
     * Get notes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
