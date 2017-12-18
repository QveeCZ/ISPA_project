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
     * @var String $dateSTK
     *
     *
     * @ORM\Column(name="date_stk", type="date", nullable=false)
     */
    protected $dateSTK;


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
     * @var School $school
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\School", inversedBy="schoolCars")
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
     * @return String
     */
    public function getDateSTK()
    {
        return $this->dateSTK;
    }

    /**
     * @param String $dateSTK
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
     * Unmapped property to handle file uploads
     */
    private $file;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile($file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Manages the copying of the file to the relevant place on the server
     */
    public function upload()
    {

        $uploadDir =  __DIR__ . "/../../../upload";

        // the file property can be empty if the field is not required
        if (null === $this->getFile()) {
            return;
        }

        // we use the original file name here but you should
        // sanitize it at least to avoid any security issues

        $filename = uniqid() . "_" . $this->getFile()->getClientOriginalName();

        // move takes the target directory and target filename as params
        $this->getFile()->move(
            $uploadDir,
            $filename
        );

        if($this->filename){
            unlink($uploadDir . "/" . $this->filename);
        }

        // set the path property to the filename where you've saved the file
        $this->filename = $filename;

        // clean up the file property as you won't need it anymore
        $this->setFile(null);
    }

    /**
     * Lifecycle callback to upload the file to the server
     */
    public function lifecycleFileUpload()
    {
        $this->upload();
    }

    /**
     * Updates the hash value to force the preUpdate and postUpdate events to fire
     */
    public function refreshUpdated()
    {
        $this->setUpdated(new \DateTime());
    }


    /**
     * @var \DateTime $updated
     *
     *
     * @ORM\Column(name="updated", type="datetime", nullable=false)
     */
    protected $updated;

    /**
     * @var String $filename
     *
     *
     * @ORM\Column(name="filename", type="string", nullable=true)
     */
    protected $filename;

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return String
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param String $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }






// ... the rest of your class lives under here, including the generated fields
//     such as filename and updated
}