<?php
namespace CourseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SchoolBundle\Entity\School;

/**
 * SchoolBundle\Entity\School
 *
 * @ORM\Entity
 * @ORM\Table(name="courses")
 */
class Course
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
     * @var String $name
     *
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;
    /**
     * @var Integer $capacity
     *
     *
     * @ORM\Column(name="capacity", type="integer", nullable=false)
     */
    protected $capacity;

    /**
     * @var School $school
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\School", inversedBy="schoolCourses")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id")
     */
    protected $school;


    /**
     * @var ArrayCollection $courseRegistrations
     *
     * @ORM\OneToMany(targetEntity="CourseBundle\Entity\Registration", mappedBy="course", cascade={ "remove"}, orphanRemoval=true)
     */
    protected $courseRegistrations;


    public function __construct()
    {
        $this->courseRegistrations = new ArrayCollection();
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
     * @return int
     */
    public function getCapacity()
    {
        return $this->capacity;
    }

    /**
     * @param int $Capacity
     */
    public function setCapacity($capacity)
    {
        $this->capacity = $capacity;
    }

    /**
     * @return String
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName($name)
    {
        $this->name = $name;
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

    /**
     * @return ArrayCollection
     */
    public function getCourseRegistrations()
    {
        return $this->courseRegistrations;
    }

    /**
     * @param ArrayCollection $courseRegistrations
     */
    public function setCourseRegistrations($courseRegistrations)
    {
        $this->courseRegistrations = $courseRegistrations;
    }

    /**
     *
     * @param Registration $courseRegistrations
     */
    public function addCourseRegistrations(Registration $courseRegistrations)
    {
        $courseRegistrations->setCourse($this);
        $this->courseRegistrations->add($courseRegistrations);
    }


    /**
     * Remove translations
     *
     * @param Registration $courseRegistrations
     */
    public function removeCourseRegistrations(Registration $courseRegistrations)
    {
        $this->courseRegistrations->removeElement($courseRegistrations);
    }

    public function __toString()
    {
        return ($this->name) ? $this->name : "";
    }


}