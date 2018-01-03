<?php

namespace CourseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SchoolBundle\Entity\Lector;
use SchoolBundle\Entity\School;

/**
 * CourseBundle\Entity\Lecture
 *
 * @ORM\Entity
 * @ORM\Table(name="lectures")
 */
class Lecture
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
     * @var Integer $length
     *
     *
     * @ORM\Column(name="length", type="integer", nullable=false)
     */
    protected $length;

    /**
     * @var Registration $courseRegistration
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\Registration", inversedBy="registrationLectures", cascade={"persist"})
     * @ORM\JoinColumn(name="course_registration_id", referencedColumnName="id", nullable=false, unique=true)
     */
    protected $courseRegistration;

    /**
     * @var LectureType $lectureType
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\LectureType")
     * @ORM\JoinColumn(name="lecture_type_id", referencedColumnName="name")
     */
    protected $lectureType;

    /**
     * @var Lector $lector
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\Lector", inversedBy="lectorLectures")
     * @ORM\JoinColumn(name="lector_id", referencedColumnName="id")
     */
    protected $lector;

    /**
     * @var \DateTime $dateLecture
     *
     *
     * @ORM\Column(name="date_lecture", type="date", nullable=false)
     */
    protected $dateLecture;

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
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param int $length
     */
    public function setLength($length)
    {
        $this->length = $length;
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
     * @return LectureType
     */
    public function getLectureType()
    {
        return $this->lectureType;
    }

    /**
     * @param LectureType $lectureType
     */
    public function setLectureType($lectureType)
    {
        $this->lectureType = $lectureType;
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
     * @return \DateTime
     */
    public function getDateLecture()
    {
        return $this->dateLecture;
    }

    /**
     * @param \DateTime $dateLecture
     */
    public function setDateLecture($dateLecture)
    {
        $this->dateLecture = $dateLecture;
    }




    public function __toString()
    {

        if(!$this->id){
            return "";
        }

        $lengthString = "hodina";

        if ($this->length > 1) {
            $lengthString = "hodiny";
        } else if ($this->length > 4) {
            $lengthString = "hodin";
        }

        return $this->lectureType . ' o délce ' . $this->length . " " . $lengthString . ", lektor " . $this->lector->getSurname();
    }


}