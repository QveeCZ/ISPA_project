<?php

namespace SchoolBundle\Entity;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\Ride;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ImageBundle\Entity\LectorImage;
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
     * @var ArrayCollection $lectorRides
     *
     * @ORM\OneToMany(targetEntity="CourseBundle\Entity\Ride", mappedBy="lector", cascade={ "remove"}, orphanRemoval=true)
     */
    protected $lectorRides;


    /**
     * @var ArrayCollection $lectorImages
     *
     * @ORM\OneToMany(targetEntity="ImageBundle\Entity\LectorImage", mappedBy="lector", cascade={ "remove"}, orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\OrderBy({"protocolDate" = "DESC"})
     */
    protected $lectorImages;


    /**
     * @var \DateTime $birthDate
     *
     *
     * @ORM\Column(name="date_birth", type="date", nullable=false)
     */
    protected $birthDate;
    /**
     * @var boolean $expired
     *
     *
     * @ORM\Column(name="expired", type="boolean", nullable=false)
     */
    protected $expired = false;

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

        $protocolArray = $this->getLectorImages()->toArray();

        if (empty($protocolArray)){
            return "";
        }
        /**
         * @var LectorImage $lastedical
         */
        $lastedical = $protocolArray[0];
        $ret = $lastedical->getProtocolDate();
        return $ret->format("Y-m-d");

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
     * @return ArrayCollection
     */
    public function getLectorRides()
    {
        return $this->lectorRides;
    }

    /**
     * @param ArrayCollection $lectorRides
     */
    public function setLectorRides($lectorRides)
    {
        $this->lectorRides = $lectorRides;
    }

    /**
     *
     * @param Lector $lectorRides
     */
    public function addCarRides(Ride $lectorRides)
    {
        $lectorRides->setLector($this);
        $this->lectorRides->add($lectorRides);
    }


    /**
     *
     * @param Ride $lectorRides
     */
    public function removeCarRides(Ride $lectorRides)
    {
        $this->lectorRides->removeElement($lectorRides);
    }

    /**
     * @return ArrayCollection
     */
    public function getLectorImages()
    {
        return $this->lectorImages;
    }

    /**
     * @param ArrayCollection $lectorImages
     */
    public function setLectorImages($lectorImages)
    {
        $this->lectorImages = $lectorImages;
    }

    /**
     *
     * @param LectorImage $lectorImages
     */
    public function addCarImages(LectorImage $lectorImages)
    {
        $lectorImages->setLector($this);
        $this->lectorImages->add($lectorImages);
    }


    /**
     *
     * @param LectorImage $lectorImages
     */
    public function removeCarImages(LectorImage $lectorImages)
    {
        $this->lectorImages->removeElement($lectorImages);
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    public function isExpired(){

        if($this->getLectorImages()->count() < 1){
            $this->expired = true;
            return true;
        }

        /**
         * @var LectorImage $lastProtocol
         */
        $lastProtocol = $this->getLectorImages()->first();


        if($lastProtocol->getProtocolDate()->diff(new \DateTime())->years > 2) {
            $this->expired = true;
            return true;
        }

        $this->expired = false;
        return false;
    }






// ... the rest of your class lives under here, including the generated fields
//     such as filename and updated

}