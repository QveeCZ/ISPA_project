<?php

namespace IOBundle\Util;


use CourseBundle\Entity\Course;
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
    private $em;

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
        $data = json_decode($json, true);

        if ($data == null) {
            throw new \Exception("json_file_invalid");
        } else {
            foreach ($data as $row) {
                if (isset($row['email'])) {
                    if (isset($row['name']) && isset($row['surname']) && isset($row['dateMedical']) && isset($row['phone'])) {
                        $lector = new Lector();
                        $lector->setName($row['name']);
                        $lector->setSurname($row['surname']);
                        $lector->setEmail($row['email']);
                        $date = new \DateTime($row['dateMedical']);
                        $lector->setDateMedical($date);
                        $lector->setPhone($row['phone']);
                        $lector->setSchool($school);

                        $this->em->persist($lector);
                        $this->em->flush();
                    } else {
                        throw new \Exception("json_file_lector invalid");
                    }
                } else if (isset($row['spz'])) {
                    if (isset($row['color']) && isset($row['dateSTK']) && isset($row['condition']) && isset($row['carType'])) {
                        $car = new Car();
                        $car->setSchool($school);
                        $car->setSpz($row['spz']);
                        $car->setColor($row['color']);
                        $car->setCondition($row['condition']);
                        $date = new \DateTime($row['dateSTK']);
                        $car->setDateSTK($date);
                        $car->setCarType($row['carType']);

                        $this->em->persist($car);
                        $this->em->flush();
                    } else {
                        throw new \Exception("json_file_car invalid");
                    }
                } else if (isset($row['capacity'])) {
                    if (isset($row['name'])) {
                        $course = new Course();
                        $course->setName($row['name']);
                        $course->setCapacity($row['capacity']);
                        $course->setSchool($school);


                        $this->em->persist($course);
                        $this->em->flush();
                    } else {
                        throw new \Exception("json_file_course invalid");
                    }
                } else {
                    throw new \Exception("invalide format of input json");
                }


            }
        }
    }


}