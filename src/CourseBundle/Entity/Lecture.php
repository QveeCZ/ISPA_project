<?php
namespace CourseBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
     * @ORM\Column(name="capacity", type="integer", nullable=false)
     */
    protected $length;

    /**
     * @var Registration $courseRegistration
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\Registration", inversedBy="registrationLectures")
     * @ORM\JoinColumn(name="course_registration_id", referencedColumnName="id")
     */
    protected $courseRegistration;

    /**
     * @var LectureType $lectureType
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\LectureType")
     * @ORM\JoinColumn(name="lecture_type_id", referencedColumnName="name")
     */
    protected $lectureType;

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




    public function __toString()
    {
        return (string)$this->id;
    }


}