<?php

namespace IOBundle\Util;


use CourseBundle\Entity\Course;
use CourseBundle\Entity\Registration;
use DateTime;
use Doctrine\ORM\EntityManager;
use DOMDocument;
use SchoolBundle\Entity\Car;
use SchoolBundle\Entity\Lector;
use SchoolBundle\Entity\School;
use Symfony\Component\HttpFoundation\File\File;

class CSVImport extends BaseImport
{

    const AUTA = 'auta';
    const LEKTORI = 'lektori';
    const KURZY = 'kurzy';
    const PRIHLASKY = 'prihlasky';

    /** @var  EntityManager */
    private $em;

    /**
     * XMLImport constructor.
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
        $csv = file_get_contents($file->getRealPath());
        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $csv);
        rewind($stream);
        $stav = self::AUTA;

        $i = 0;

        //nacteni dat do pole
        while ($array = fgetcsv($stream,null,';'))
        {

            if(in_array($array['0'],[self::AUTA,self::KURZY,self::LEKTORI,self::PRIHLASKY]) && $array[1] ==  '')
            {
                $stav = $array['0'];
            }
            else
            {
                $data[$stav][] = $array;
            }
        }

        $this->importCars($data[self::AUTA], $school);
        $this->importLectors($data[self::LEKTORI], $school);
        $this->importCourses($data, $school);
    }


    /**
     * @param \SimpleXMLElement[] $carListXml
     * @param School $school
     */
    private function importCars($carListXml, $school)
    {
        foreach ($carListXml as $carXml) {
            $car = new Car();
            $car->setColor($carXml[0]);
            $car->setSpz($carXml[1]);
            $car->setDateSTK(DateTime::createFromFormat('Y-m-d', $carXml[2]));
            $car->setCondition($carXml[3]);
            $car->setCarType($carXml[4]);
            $car->setSchool($school);
            $this->em->persist($car);
        }

        $this->em->flush();
    }

    /**
     * @param \SimpleXMLElement[] $lectorListXML
     * @param School $school
     */
    private function importLectors($lectorListXML, $school)
    {
        foreach ($lectorListXML as $lectorXml) {
            $lector = new Lector();
            $lector->setId($lectorXml[0]);
            $lector->setEmail($lectorXml[1]);
            $lector->setName($lectorXml[2]);
            $lector->setSurname($lectorXml[3]);
            $lector->setPhone($lectorXml[4]);
            $lector->setDateMedical(DateTime::createFromFormat('Y-m-d', $lectorXml[5]));
            $lector->setSchool($school);
            $this->em->persist($lector);
        }

        $this->em->flush();

    }

    /**
     * @param \SimpleXMLElement[] $courseListXML
     * @param School $school
     */
    private function importCourses($courseListXML, $school)
    {

        foreach ($courseListXML[self::KURZY] as $courseXml) {
            $course = new Course();
            $course->setCapacity($courseXml[3]);
            $course->setName($courseXml[2]);
            $course->setSchool($school);
            $this->em->persist($course);
            $this->importCourseRegistrations($courseListXML[self::PRIHLASKY],$course);

        }



        $this->em->flush();
    }


    /**
     * @param \SimpleXMLElement[] $registrationListXML
     * @param Course $course
     */
    private function importCourseRegistrations($registrationListXML,$course)
    {
        foreach ($registrationListXML as $registrationXml) {
            $registration = new Registration();
            $registration->setName($registrationXml[1]);
            $registration->setSurname($registrationXml[2]);
            $registration->setCourse($course);
            $this->em->persist($registration);
        }

        $this->em->flush();

    }





}