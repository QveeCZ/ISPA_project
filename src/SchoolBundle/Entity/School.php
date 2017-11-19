<?php

namespace SchoolBundle\Entity;

use CourseBundle\Entity\Course;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


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
     * @var String $name
     * @Assert\Length(
     *      min = 8,
     *      max = 8,
     * )
     * @Assert\Type(
     *     type="integer",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @ORM\Column(name="ico", type="string", nullable=false)
     */
    protected $ico;

    /**
     * @return String
     */
    public function getIco()
    {
        return $this->ico;
    }

    /**
     * @param String $ico
     */
    public function setIco($ico)
    {
        $this->ico = $ico;
    }

    /**
     * @return String
     */
    public function getKontakt()
    {
        return $this->kontakt;
    }

    /**
     * @param String $kontakt
     */
    public function setKontakt($kontakt)
    {
        $this->kontakt = $kontakt;
    }

    /**
     * @return String
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * @param String $web
     */
    public function setWeb($web)
    {
        $this->web = $web;
    }

    /**
     * @var String $name
     *
     *
     * @ORM\Column(name="kontakt", type="string", nullable=false)
     */
    protected $kontakt;

    /**
     * @var String $name
     *
     *
     * @ORM\Column(name="web", type="string", nullable=false)
     */
    protected $web;

    /**
     * @var ArrayCollection $schoolCourses
     *
     * @ORM\OneToMany(targetEntity="CourseBundle\Entity\Course", mappedBy="school", cascade={ "remove"}, orphanRemoval=true)
     */
    protected $schoolCourses;

    /**
     * @var ArrayCollection $schoolCars
     *
     * @ORM\OneToMany(targetEntity="SchoolBundle\Entity\Car", mappedBy="school", cascade={ "remove"}, orphanRemoval=true)
     */
    protected $schoolCars;


    /**
     * @var ArrayCollection $schoolCourses
     *
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\User", mappedBy="school", cascade={ "remove"}, orphanRemoval=true)
     */
    protected $schoolUsers;

    /**
     * @var ArrayCollection $schoolLectors
     *
     * @ORM\OneToMany(targetEntity="SchoolBundle\Entity\Lector", mappedBy="school", cascade={ "remove"}, orphanRemoval=true)
     */
    protected $schoolLectors;

    public function __construct()
    {
        $this->schoolCars = new ArrayCollection();
        $this->schoolCourses = new ArrayCollection();
        $this->schoolLectors = new ArrayCollection();
        $this->schoolUsers = new ArrayCollection();
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


    /**
     * @return ArrayCollection
     */
    public function getSchoolCars()
    {
        return $this->schoolCars;
    }

    /**
     * @param ArrayCollection $schoolCars
     */
    public function setSchoolCars($schoolCars)
    {
        $this->schoolCourses = $schoolCars;
    }

    /**
     *
     * @param Car $schoolCars
     */
    public function addSchoolCars(Course $schoolCars)
    {
        $schoolCars->setSchool($this);
        $this->schoolCourses->add($schoolCars);
    }


    /**
     * Remove translations
     *
     * @param Car $translations
     */
    public function removeSchoolCars(Course $schoolCars)
    {
        $this->schoolCourses->removeElement($schoolCars);
    }

    /**
     * @return ArrayCollection
     */
    public function getSchoolLectors()
    {
        return $this->schoolLectors;
    }

    /**
     * @param ArrayCollection $schoolLectors
     */
    public function setSchoolLectors($schoolLectors)
    {
        $this->schoolLectors = $schoolLectors;
    }

    /**
     *
     * @param Lector $schoolLectors
     */
    public function addSchoolLectors(Lector $schoolLectors)
    {
        $schoolLectors->setSchool($this);
        $this->schoolLectors->add($schoolLectors);
    }


    /**
     * Remove translations
     *
     * @param Lector $translations
     */
    public function removeSchoolLectors(Lector $schoolLectors)
    {
        $this->schoolLectors->removeElement($schoolLectors);
    }

    /**
     * @return ArrayCollection
     */
    public function getSchoolUsers()
    {
        return $this->schoolLectors;
    }

    /**
     * @param ArrayCollection $schoolUsers
     */
    public function setSchoolUsers($schoolUsers)
    {
        $this->schoolLectors = $schoolUsers;
    }

    /**
     *
     * @param Lector $schoolUsers
     */
    public function addSchoolUsers(Lector $schoolUsers)
    {
        $schoolUsers->setSchool($this);
        $this->schoolLectors->add($schoolUsers);
    }


    /**
     * Remove translations
     *
     * @param Lector $translations
     */
    public function removeSchoolUsers(Lector $schoolUsers)
    {
        $this->schoolLectors->removeElement($schoolUsers);
    }


    public function __toString()
    {
        return ($this->name) ? $this->name : "";
    }


}