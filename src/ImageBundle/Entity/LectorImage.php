<?php
namespace ImageBundle\Entity;
use SchoolBundle\Entity\Lector;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ImageBundle\Entity\LectorImage
 *
 * @ORM\Entity
 * @ORM\Table(name="lector_images")
 */
class LectorImage extends  ImageMain
{

    /**
     * @var Lector $lector
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\Lector", inversedBy="lectorRides")
     * @ORM\JoinColumn(name="lector_id", referencedColumnName="id")
     */
    protected $lector;

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
    public function setLector(Lector $lector)
    {
        $this->lector = $lector;
    }




}