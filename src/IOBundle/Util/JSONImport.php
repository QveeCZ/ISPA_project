<?php

namespace IOBundle\Util;


use CourseBundle\Entity\Course;
use CourseBundle\Entity\Registration;
use DateTime;
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
            foreach ($data as $row => $value) {
                if ($row == "lectors") {
                    foreach ($value as $lectorArray) {
                        $this->importLectors($lectorArray, $school);
                    }

                } else if ($row == "cars") {
                    foreach ($value as $carArray) {
                        $this->importCars($carArray, $school);
                    }
                } else if ($row == "courses") {
                    foreach ($value as $courseArray) {
                        $this->importCourses($courseArray, $school);
                    }

                } else {
                    throw new \Exception("invalide format of input json");
                }


            }
            $this->em->flush();
        }
    }

    private function importLectors($lectorArray, $school)
    {
        if (isset($lectorArray['name']) && isset($lectorArray['email']) && isset($lectorArray['surname']) && isset($lectorArray['dateMedical']) && isset($lectorArray['phone'])) {
            $lector = new Lector();
            $lector->setName($lectorArray['name']);
            $lector->setSurname($lectorArray['surname']);
            $lector->setEmail($lectorArray['email']);
            $lector->setDateMedical(DateTime::createFromFormat('Y-m-d', $lectorArray['dateMedical']));
            $lector->setPhone($lectorArray['phone']);
            $lector->setSchool($school);

            $this->em->persist($lector);
        } else {
            throw new \Exception("json_file_lector invalid");
        }
    }
    private function importCars($carArray, $school)
    {
        if (isset($carArray['color']) && isset($carArray['dateSTK']) && isset($carArray['spz']) && isset($carArray['condition']) && isset($carArray['carType'])) {
            $car = new Car();
            $car->setSchool($school);
            $car->setSpz($carArray['spz']);
            $car->setColor($carArray['color']);
            $car->setCondition($carArray['condition']);
            $car->setDateSTK(DateTime::createFromFormat('Y-m-d', $carArray['dateSTK']));
            $car->setCarType($carArray['carType']);

            $this->em->persist($car);
        } else {
            throw new \Exception("json_file_car invalid");
        }

    }
    private function importCourses($courseArray, $school)
    {
        if (isset($courseArray['name']) && isset($courseArray['capacity'])) {
            $course = new Course();
            $course->setName($courseArray['name']);
            $course->setCapacity($courseArray['capacity']);
            $course->setSchool($school);
            if (isset($courseArray['registrations'])) {
                foreach ($courseArray['registrations'] as $registrationArray) {
                    $this->importRegistrations($registrationArray, $course);
                }
            }
            $this->em->persist($course);
        } else {
            throw new \Exception("json_file_course invalid");
        }
    }
    private function importRegistrations($registrationArray, $course)
    {
        if (isset($registrationArray['name']) && isset($registrationArray['surname'])) {
            $registration = new Registration();
            $registration->setName($registrationArray['name']);
            $registration->setSurname($registrationArray['surname']);
            $registration->setCourse($course);
            $this->em->persist($registration);
        } else {
            throw new \Exception("json_file_course_registration invalid");
        }
    }
}