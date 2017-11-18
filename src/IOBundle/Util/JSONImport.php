<?php

namespace IOBundle\Util;


use DOMDocument;
use SchoolBundle\Entity\Car;
use SchoolBundle\Entity\Lector;
use SchoolBundle\Entity\School;
use Symfony\Component\HttpFoundation\File\File;

class JSONImport extends BaseImport
{



    /**
     * @param File $file
     * @param School $school
     * @throws \Exception
     * @return void
     */
    public function doImport($file, $school)
    {
        $em = $this->getDoctrine()->getManager();

        $repoLector = $em->getRepository('SchoolBundle:Lector');
        $repoCar = $em->getRepository('SchoolBundle:Car');

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
                $lector->setDateMedical($row['dateMedical']);
                $lector->setPhone($row['phone']);
                $lector->setSchool($school);

                $em->persist($lector);
                $em->flush();
            }else if($row['spz']!=null) {
                $car = new Car();
                $car->setSchool($school);
                $car->setSpz($row['spz']);
                $car->setColor($row['color']);
                $car->setCondition($row['condition']);
                $car->setDateSTK($row['dateSTK']);

                $em->persist($car);
                $em->flush();
            }


            }
        }
    }



}