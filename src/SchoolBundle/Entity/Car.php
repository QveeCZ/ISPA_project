<?php

namespace SchoolBundle\Entity;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\Ride;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ImageBundle\Entity\CarImage;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * SchoolBundle\Entity\Car
 *
 * @ORM\Entity
 * @ORM\Table(name="cars")
 */
class Car
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
     * @var String $spz
     *
     *
     * @ORM\Column(name="spz", type="string", nullable=false)
     */
    protected $spz;


    /**
     * @var String $color
     *
     *
     * @ORM\Column(name="color", type="string", nullable=false)
     */
    protected $color;


    /**
     * @var String $condition
     *
     *
     * @ORM\Column(name="car_condition", type="string", nullable=false)
     */
    protected $condition;

    /**
     * @var String $carType
     *
     *
     * @ORM\Column(name="car_type", type="string", nullable=false)
     */
    protected $carType;

    /**
     * @var integer $carType
     *
     *
     * @ORM\Column(name="fuel_consumption", type="integer", nullable=false)
     */
    protected $fuelConsumption;

    /**
     * @var School $school
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\School", inversedBy="schoolCars")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     */
    protected $school;


    /**
     * @var ArrayCollection $carRides
     *
     * @ORM\OneToMany(targetEntity="CourseBundle\Entity\Ride", mappedBy="car", cascade={ "remove"}, orphanRemoval=true)
     */
    protected $carRides;


    /**
     * @var ArrayCollection $carImages
     *
     * @ORM\OneToMany(targetEntity="ImageBundle\Entity\CarImage", mappedBy="car", cascade={ "remove"}, orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\OrderBy({"protocolDate" = "DESC"})
     */
    protected $carImages;
    /**
     * @var boolean $expired
     *
     *
     * @ORM\Column(name="expired", type="boolean", nullable=false)
     */
    protected $expired = false;

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
     * @return String
     */
    public function getSpz()
    {
        return $this->spz;
    }

    /**
     * @param String $spz
     */
    public function setSpz($spz)
    {
        $this->spz = $spz;
    }

    /**
     * @return String
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param String $color
     */
    public function setColor($color)
    {
        $this->color = $color;
    }

    /**
     * @return String
     */
    public function getDateSTK()
    {

        $protocolArray = $this->getCarImages()->toArray();

        if (empty($protocolArray)){
            return "";
        }
        /**
         * @var CarImage $lastSTK
         */
        $lastSTK = $protocolArray[0];
        $ret = $lastSTK->getProtocolDate();
        return $ret->format("Y-m-d");
    }

    /**
     * @return String
     */
    public function getCondition()
    {
        return $this->condition;
    }

    /**
     * @param String $condition
     */
    public function setCondition($condition)
    {
        $this->condition = $condition;
    }

    /**
     * @return String
     */
    public function getCarType()
    {
        return $this->carType;
    }

    /**
     * @param String $carType
     */
    public function setCarType($carType)
    {
        $this->carType = $carType;
    }

    /**
     * @return School
     */
    public function getSchool()
    {
        return $this->school;
    }

    /**
     * @param School $school
     */
    public function setSchool($school)
    {
        $this->school = $school;
    }

    function __toString()
    {
        return ($this->getSpz()) ? $this->getSpz() : "";
    }

    /**
     * @return ArrayCollection
     */
    public function getCarRides()
    {
        return $this->carRides;
    }

    /**
     * @param ArrayCollection $carRides
     */
    public function setCarRides($carRides)
    {
        $this->carRides = $carRides;
    }

    /**
     *
     * @param Ride $carRides
     */
    public function addCarRides(Ride $carRides)
    {
        $carRides->setCar($this);
        $this->carRides->add($carRides);
    }

    public function isExpired(){

        if($this->getCarImages()->count() < 1){
            $this->expired = true;
            return true;
        }

        /**
         * @var CarImage $lastProtocol
         */
        $lastProtocol = $this->getCarImages()->first();


        if($lastProtocol->getProtocolDate()->diff(new DateTime())->years > 2) {
            $this->expired = true;
            return true;
        }

        $this->expired = false;
        return false;
    }


    /**
     *
     * @param Ride $carRides
     */
    public function removeCarRides(Ride $carRides)
    {
        $this->carRides->removeElement($carRides);
    }

    /**
     * @return ArrayCollection
     */
    public function getCarImages()
    {
        return $this->carImages;
    }

    /**
     * @param ArrayCollection $carImages
     */
    public function setCarImages($carImages)
    {
        $this->carImages = $carImages;
    }

    /**
     *
     * @param CarImage $carImages
     */
    public function addCarImages(CarImage $carImages)
    {
        $carImages->setCar($this);
        $this->carImages->add($carImages);
    }


    /**
     *
     * @param CarImage $carImages
     */
    public function removeCarImages(CarImage $carImages)
    {
        $this->carImages->removeElement($carImages);
    }


    public function getTotalRideLength() {
        $i = 0;
        /**
         * @var Ride $ride
         */
        foreach ($this->getCarRides() as $ride) {
            $i +=  $ride->getLength();
        }
        return $i;
    }

    /**
     * @return int
     */
    public function getFuelConsumption()
    {
        return $this->fuelConsumption;
    }

    /**
     * @param int $fuelConsumption
     */
    public function setFuelConsumption($fuelConsumption)
    {
        $this->fuelConsumption = $fuelConsumption;
    }





// ... the rest of your class lives under here, including the generated fields
//     such as filename and updated
}