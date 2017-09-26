<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 */
class Event
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
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $beginningDateEvent = null;    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\DateTime()
     */
    private $endingDateEvent ;

    /**
     * @var string
     *
     * @ORM\Column(name="place", type="string", length=255, nullable=true)
     */
    private $place;    
    
    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $lat;    
    
    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=true)
     */
    private $lng;
    
    /**
     * Constructor
     */
    public function __construct() 
    {
        $this->endingDateEvent= null;
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
     * Set dateEvent
     *
     * @param \DateTime $dateEvent
     *
     * @return Event
     */
    public function setDateEvent($dateEvent)
    {
        $this->dateEvent = $dateEvent;

        return $this;
    }

    /**
     * Get dateEvent
     *
     * @return \DateTime
     */
    public function getDateEvent()
    {
        return $this->dateEvent;
    }

    /**
     * Set place
     *
     * @param string $place
     *
     * @return Event
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * Set beginningDateEvent
     *
     * @param \DateTime $beginningDateEvent
     *
     * @return Event
     */
    public function setBeginningDateEvent($beginningDateEvent)
    {
        $this->beginningDateEvent = $beginningDateEvent;

        return $this;
    }

    /**
     * Get beginningDateEvent
     *
     * @return \DateTime
     */
    public function getBeginningDateEvent()
    {
        return $this->beginningDateEvent;
    }

    /**
     * Set endingDateEvent
     *
     * @param \DateTime $endingDateEvent
     *
     * @return Event
     */
    public function setEndingDateEvent($endingDateEvent)
    {
        $this->endingDateEvent = $endingDateEvent;

        return $this;
    }

    /**
     * Get endingDateEvent
     *
     * @return \DateTime
     */
    public function getEndingDateEvent()
    {
        return $this->endingDateEvent;
    }

    /**
     * Set lat
     *
     * @param integer $lat
     *
     * @return Event
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return integer
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lng
     *
     * @param integer $lng
     *
     * @return Event
     */
    public function setLng($lng)
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * Get lng
     *
     * @return integer
     */
    public function getLng()
    {
        return $this->lng;
    }
}
