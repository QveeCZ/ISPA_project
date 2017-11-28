<?php

namespace CourseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CourseBundle\Entity\Registration
 *
 * @ORM\Entity
 * @ORM\Table(name="course_registrations")
 */
class Registration
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
     * @var Course $course
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\Course", inversedBy="courseRegistrations")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    protected $course;

    /**
     * @var String $name
     *
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var String $name
     *
     *
     * @ORM\Column(name="surname", type="string", nullable=false)
     */
    protected $surname;

    /**
     * @var String $email
     *
     *
     * @ORM\Column(name="email", type="string", nullable=false)
     */
    protected $email;

    /**
     * @var String $birthDate
     *
     *
     * @ORM\Column(name="birthDate", type="date", nullable=false)
     */
    protected $birthDate;

    /**
     * @var \DateTime $created
     *
     *
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $created;


    /**
     * @var ArrayCollection $registrationLectures
     *
     * @ORM\OneToMany(targetEntity="CourseBundle\Entity\Lecture", mappedBy="courseRegistration", cascade={ "persist", "remove"}, orphanRemoval=true)
     */
    protected $registrationLectures;


    /**
     * @var ArrayCollection $registrationRides
     *
     * @ORM\OneToMany(targetEntity="CourseBundle\Entity\Ride", mappedBy="courseRegistration", cascade={ "persist", "remove"}, orphanRemoval=true)
     */
    protected $registrationRides;

    function __construct()
    {
        if (!$this->created) {
            $this->created = new \DateTime();
        }
        $this->registrationLectures = new ArrayCollection();
        $this->registrationRides = new ArrayCollection();
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
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param Course $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
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
     * @return String
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param String $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * @return String
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param String $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return String
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param String $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): \DateTime
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
     * @return ArrayCollection
     */
    public function getRegistrationLectures()
    {
        return $this->registrationLectures;
    }

    /**
     * @param ArrayCollection $registrationLectures
     */
    public function setRegistrationLectures($registrationLectures)
    {
        $this->registrationLectures = $registrationLectures;
    }

    /**
     *
     * @param Lecture $courseRegistrations
     */
    public function addRegistrationLectures(Lecture $registrationLectures)
    {
        $registrationLectures->setCourseRegistration($this);
        $this->registrationLectures->add($registrationLectures);
    }


    /**
     *
     * @param Lecture $registrationLectures
     */
    public function removeRegistrationLectures(Lecture $registrationLectures)
    {
        $this->registrationLectures->removeElement($registrationLectures);
    }

    /**
     * @return ArrayCollection
     */
    public function getRegistrationRides()
    {
        return $this->registrationRides;
    }

    /**
     * @param ArrayCollection $registrationLectures
     */
    public function setRegistrationRides($registrationRides)
    {
        $this->registrationRides = $registrationRides;
    }

    /**
     *
     * @param Lecture $courseRegistrations
     */
    public function addRegistrationRides(Ride $registrationRide)
    {
        $registrationRide->setCourseRegistration($this);
        $this->registrationRides->add($registrationRide);
    }


    /**
     *
     * @param Lecture $registrationLectures
     */
    public function removeRegistrationRides(Ride $registrationRide)
    {
        $this->registrationRides->removeElement($registrationRide);
    }


    public function __toString()
    {
        $lectures = implode(', ', $this->getRegistrationLectures()->toArray());
        if($lectures){
            $lectures = ", Teorie: " . $lectures;
        }
        $rides = implode(', ', $this->getRegistrationRides()->toArray());
        if($rides){
            $rides = ", Jízdy: " . $rides;
        }

        return ($this->id) ? $this->getSurname() . " " . $this->getName() . $lectures . $rides : "";
    }


}