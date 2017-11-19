<?php

namespace IOBundle\Util;


use DOMDocument;
use SchoolBundle\Entity\Car;
use SchoolBundle\Entity\Lector;
use SchoolBundle\Entity\School;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\EntityManager;

class JSONImport extends BaseImport
{

    /**
     * @var EntityManager $em
     */
    protected $em;

    /**
     * SystemExport constructor.
     * @param EntityManager $em
     */
    public function __construct($em)
    {
        $this->em = $em;
    }
    /**
     * @param File $file
     * @param School $school
     * @throws \Exception
     * @return void
     */
    public function doImport($file, $school)
    {
        $json = file_get_contents($file->getRealPath());
        $data = json_decode($json,true);

        if($data == null){
            throw new \Exception("json_file_invalid");
        }else
        {
            foreach ($data as $row)
            {
            if($row['name']!=null){
                $lector = new Lector();
                $lector->setName($row['name']);
                $lector->setSurname($row['surname']);
                $lector->setEmail($row['email']);
                //$lector->setDateMedical($row['dateMedical']);
                $date = new \DateTime($row['dateMedical']);
                $lector->setDateMedical($date);
                $lector->setPhone($row['phone']);
                $lector->setSchool($school);

                $this->em->persist($lector);
                $this->em->flush();
            }else if($row['spz']!=null) {
                $car = new Car();
                $car->setSchool($school);
                $car->setSpz($row['spz']);
                $car->setColor($row['color']);
                $car->setCondition($row['condition']);
                $date = new \DateTime($row['dateSTK']);
                $car->setDateSTK($date);

                $this->em->persist($car);
                $this->em->flush();
            }


            }
        }
    }



}