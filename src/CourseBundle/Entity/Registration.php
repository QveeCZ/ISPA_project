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


    function __construct()
    {
        if (!$this->created) {
            $this->created = new \DateTime();
        }
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
     * @param String email
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


    public function __toString()
    {
        return ($this->id) ? $this->getCourse()->getName() . " - " . $this->getSurname() . " " . $this->getName() : "";
    }


}