<?php
namespace ImageBundle\Entity;
use SchoolBundle\Entity\Car;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ImageBundle\Entity\CarImage
 *
 * @ORM\Entity
 * @ORM\Table(name="car_images")
 */
class CarImage extends ImageMain
{

    /**
     * @var Car $car
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\Car", inversedBy="carRides")
     * @ORM\JoinColumn(name="car_id", referencedColumnName="id")
     */
    protected $car;

    /**
     * @return Car
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * @param Car $car
     */
    public function setCar(Car $car)
    {
        $this->car = $car;
    }



}