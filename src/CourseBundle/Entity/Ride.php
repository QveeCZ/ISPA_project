<?php
namespace CourseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SchoolBundle\Entity\Car;
use SchoolBundle\Entity\Lector;
use SchoolBundle\Entity\School;

/**
 * CourseBundle\Entity\Lecture
 *
 * @ORM\Entity
 * @ORM\Table(name="course_ride")
 */
class Ride
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var \DateTime $dateRide
     *
     *
     * @ORM\Column(name="date_ride", type="date", nullable=false)
     */
    protected $dateRide;

    /**
     * @var Registration $courseRegistration
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\Registration", inversedBy="registrationLectures", cascade={"persist"})
     * @ORM\JoinColumn(name="course_registration_id", referencedColumnName="id", nullable=false, unique=true)
     */
    protected $courseRegistration;

    /**
     * @var Lector $lector
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\Lector", inversedBy="lectorRides")
     * @ORM\JoinColumn(name="lector_id", referencedColumnName="id")
     */
    protected $lector;

    /**
     * @var Car $car
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\Car", inversedBy="carRides")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     */
    protected $car;

    /**
     * @var \DateTime $created
     *
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var integer $length
     *
     *
     * @ORM\Column(name="length", type="integer", nullable=false)
     */
    protected $length;


    function __construct()
    {
        $this->created = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return \DateTime
     */
    public function getDateRide()
    {
        return $this->dateRide;
    }

    /**
     * @param \DateTime $dateRide
     */
    public function setDateRide($dateRide)
    {
        $this->dateRide = $dateRide;
    }




    /**
     * @return Registration
     */
    public function getCourseRegistration()
    {
        return $this->courseRegistration;
    }

    /**
     * @param Registration $courseRegistration
     */
    public function setCourseRegistration($courseRegistration)
    {
        $this->courseRegistration = $courseRegistration;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * @return Lector
     */
    public function getLector()
    {
        return $this->lector;
    }

    /**
     * @param Lector $lector
     */
    public function setLector($lector)
    {
        $this->lector = $lector;
    }

    /**
     * @return Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param Car $car
     */
    public function setCar(Car $car)
    {
        $this->car = $car;
    }

    /**
     * @return integer
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param integer $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    public function __toString()
    {

        $rideDate = "";

        if($this->getDateRide()){
            $rideDate = $this->getDateRide()->format("d.m.Y");
        }

        return 'Jízda ' . $rideDate . ", lektor " . $this->lector->getSurname() . ", délka " . $this->length . " km.";
    }


}