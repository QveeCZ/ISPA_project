<?php

namespace SchoolBundle\Entity;

use CourseBundle\Entity\Course;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * SchoolBundle\Entity\Lector
 *
 * @ORM\Entity
 * @ORM\Table(name="lectors")
 */
class Lector
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
     * @var String $email
     *
     *
     * @ORM\Column(name="email", type="string", nullable=false)
     */
    protected $email;

    /**
     * @var String $name
     *
     *
     * @ORM\Column(name="name", type="string", nullable=false)
     */
    protected $name;

    /**
     * @var integer $pocet_deti
     *
     *
     * @ORM\Column(name="pocet_deti", type="integer", nullable=false)
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      minMessage = "Minimalne 0",
     *      maxMessage = "Nejvice 100 :D"
     * )
     */
    protected $pocet_deti;

    /**
     * @return int
     */
    public function getPocetDeti()
    {
        return $this->pocet_deti;
    }

    /**
     * @param int $pocet_deti
     */
    public function setPocetDeti($pocet_deti)
    {
        $this->pocet_deti = $pocet_deti;
    }

    /**
     * @return int
     */
    public function getHodinovaMzda()
    {
        return $this->hodinova_mzda;
    }

    /**
     * @param int $hodinova_mzda
     */
    public function setHodinovaMzda($hodinova_mzda)
    {
        $this->hodinova_mzda = $hodinova_mzda;
    }

    /**
     * @var integer $phone
     *
     *
     * @ORM\Column(name="hodinova_mzda", type="integer", nullable=false)
     *      * @Assert\Range(
     *      min = 60,
     *      max = 1000,
     *      minMessage = "Nejměně 60",
     *      maxMessage = "Nejvíce 1000"
     * )
     */
    protected $hodinova_mzda;


    /**
     * @var String $surname
     *
     *
     * @ORM\Column(name="surname", type="string", nullable=false)
     */
    protected $surname;


    /**
     * @var String $dateMedical
     *
     *
     * @ORM\Column(name="date_medical", type="date", nullable=false)
     */
    protected $dateMedical;


    /**
     * @var integer $phone
     *
     *
     * @ORM\Column(name="phone", type="integer", nullable=false)
     * @Assert\Length(
     *      min = 9,
     *      max = 9,
     *      minMessage = "Délka musí být 9",
     *      maxMessage = "Délka musí být 9"
     * )
     */
    protected $phone;

    /**
     * @var School $school
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\School", inversedBy="schoolLectors")
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
    public function getDateMedical()
    {
        return $this->dateMedical;
    }

    /**
     * @param String $dateMedical
     */
    public function setDateMedical($dateMedical)
    {
        $this->dateMedical = $dateMedical;
    }

    /**
     * @return String
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param String $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
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

    public function __toString()
    {
        return $this->getSurname() . " " . $this->getName();
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