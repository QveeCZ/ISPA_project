<?php
namespace UserBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use SchoolBundle\Entity\School;

/**
 * UserBundle\Entity\User
 *
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends \FOS\UserBundle\Model\User
{
    /**
     * @var mixed
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var School $school
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\School", inversedBy="schoolUsers")
     * @ORM\JoinColumn(name="school_id", referencedColumnName="id", nullable=true)
     */
    protected $school;

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




}