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
 * @ORM\Table(name="lecture_type")
 */
class LectureType
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="name", type="string")
     */
    protected $name;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }







    public function __toString()
    {
        return (string)$this->name;
    }


}