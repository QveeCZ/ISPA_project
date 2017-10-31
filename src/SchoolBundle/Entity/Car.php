<?php

namespace SchoolBundle\Entity;

use CourseBundle\Entity\Course;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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
     * @var \DateTime $dateSTK
     *
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    protected $dateSTK;


    /**
     * @var String $condition
     *
     *
     * @ORM\Column(name="condition", type="string", nullable=false)
     */
    protected $condition;

    /**
     * @var School $school
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\School", inversedBy="schoolCourses")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     */
    protected $school;

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
     * @return \DateTime
     */
    public function getDateSTK()
    {
        return $this->dateSTK;
    }

    /**
     * @param \DateTime $dateSTK
     */
    public function setDateSTK($dateSTK)
    {
        $this->dateSTK = $dateSTK;
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
        return $this->getSpz();
    }


}