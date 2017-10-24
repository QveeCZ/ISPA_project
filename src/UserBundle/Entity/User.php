<?php
namespace UserBundle\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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


}