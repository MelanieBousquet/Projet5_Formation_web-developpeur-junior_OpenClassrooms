<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Publication
 *
 * @ORM\Table(name="publication")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PublicationRepository")
 */
class Publication
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
     * @var bool
     *
     * @ORM\Column(name="published", type="boolean")
     */
    private $published ;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Assert\DateTime( 
     * message="Date non valide"     
     * )
     */
    private $date;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime( 
     * message="Date non valide"     
     * )
     */
    private $updatedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     * message = "Ce champ ne doit pas être vide"
     * )
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\AnimalState", mappedBy="publication", cascade={"persist"})
     * @ORM\joinColumn(onDelete="SET NULL")
     * @Assert\Valid()
     */
    private $animalState;
    
    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Image", mappedBy="publications", cascade={"persist"})
     * @Assert\Valid()
     */
    private $images;
    
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist"})
     * @Assert\Valid()
     */
    private $mainImage;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Event", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $event;
    
    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Note", mappedBy="publication", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $notes;
    
    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Place", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $place;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->notes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->date = new \DateTime();
        $this->updatedDate = new \DateTime();
        $this->published = false ;
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
     * Set published
     *
     * @param boolean $published
     *
     * @return Publication
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return bool
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Publication
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
     * Set title
     *
     * @param string $title
     *
     * @return Publication
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return Publication
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }



    /**
     * Set event
     *
     * @param \AppBundle\Entity\Event $event
     *
     * @return Publication
     */
    public function setEvent(\AppBundle\Entity\Event $event = null)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * Get event
     *
     * @return \AppBundle\Entity\Event
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * Set updatedDate
     *
     * @param \DateTime $updatedDate
     *
     * @return Publication
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get updatedDate
     *
     * @return \DateTime
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Add image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Publication
     */
    public function addImage(\AppBundle\Entity\Image $image)
    {
        $this->images[] = $image;
        $image->addPublication($this);
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
        $image->removePublication($this);
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
     * Add note
     *
     * @param \AppBundle\Entity\Note $note
     *
     * @return Publication
     */
    public function addNote(\AppBundle\Entity\Note $note)
    {
        $this->notes[] = $notes;

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
    


    /**
     * Set animalState
     *
     * @param \AppBundle\Entity\AnimalState $animalState
     *
     * @return Publication
     */
    public function setAnimalState(\AppBundle\Entity\AnimalState $animalState = null)
    {
        $this->animalState = $animalState;
        $animalState->setPublication($this);
        return $this;
    }

    /**
     * Get animalState
     *
     * @return \AppBundle\Entity\AnimalState
     */
    public function getAnimalState()
    {
        return $this->animalState;
    }

    /**
     * Set mainImage
     *
     * @param \AppBundle\Entity\Image $mainImage
     *
     * @return Publication
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
     * Set place
     *
     * @param \AppBundle\Entity\Place $place
     *
     * @return Publication
     */
    public function setPlace(\AppBundle\Entity\Place $place = null)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return \AppBundle\Entity\Place
     */
    public function getPlace()
    {
        return $this->place;
    }
}
