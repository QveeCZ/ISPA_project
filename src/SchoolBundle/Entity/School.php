<?php
namespace SchoolBundle\Entity;

use CourseBundle\Entity\Course;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * SchoolBundle\Entity\School
 *
 * @ORM\Entity
 * @ORM\Table(name="schools")
 */
class School
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
     * @var ArrayCollection $schoolCourses
     *
     * @ORM\OneToMany(targetEntity="CourseBundle\Entity\Course", mappedBy="school", cascade={ "persist", "remove"}, orphanRemoval=true)
     */
    protected $schoolCourses;

    public function __construct()
    {
        $this->schoolCourses = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getSchoolCourses()
    {
        return $this->schoolCourses;
    }

    /**
     * @param ArrayCollection $schoolCourses
     */
    public function setSchoolCourses($schoolCourses)
    {
        $this->schoolCourses = $schoolCourses;
    }

    /**
     *
     * @param Course $schoolCourse
     */
    public function addSchoolCourses(Course $schoolCourse)
    {
        $schoolCourse->setSchool($this);
        $this->schoolCourses->add($schoolCourse);
    }


    /**
     * Remove translations
     *
     * @param Course $translations
     */
    public function removeSchoolCourses(Course $schoolCourses)
    {
        $this->schoolCourses->removeElement($schoolCourses);
    }

    public function __toString()
    {
        return ($this->name) ? $this->name : "";
    }


}