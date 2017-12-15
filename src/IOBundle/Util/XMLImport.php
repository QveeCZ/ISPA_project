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

class XMLImport extends BaseImport
{

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

        $xml = simplexml_load_file($file->getRealPath(), 'SimpleXmlElement', LIBXML_NOERROR + LIBXML_ERR_FATAL + LIBXML_ERR_NONE);

        if (!$this->is_valid_xml(file_get_contents($file->getRealPath()))) {
            throw new \Exception("XML není validní");
        }

        if($xml->cars){
            $this->importCars($xml->cars->car, $school);
        }
        if($xml->lectors){
            $this->importLectors($xml->lectors->lector, $school);
        }
        if($xml->courses){
            $this->importCourses($xml->courses->course, $school);
        }


    }


    /**
     * @param \SimpleXMLElement[] $carListXml
     * @param School $school
     */
    private function importCars($carListXml, $school)
    {

        foreach ($carListXml as $carXml) {
            $car = new Car();
            $car->setColor($carXml->color);
            $car->setSpz($carXml->SPZ);
            $car->setDateSTK(DateTime::createFromFormat('Y-m-d', $carXml->date_stk));
            $car->setCarType($carXml->car_type);
            $car->setCondition($carXml->car_condition);
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
            $lector->setEmail($lectorXml->email);
            $lector->setName($lectorXml->name);
            $lector->setSurname($lectorXml->surname);
            $lector->setPhone($lectorXml->phone);
            $lector->setDateMedical(DateTime::createFromFormat('Y-m-d', $lectorXml->date_medical));
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

        foreach ($courseListXML as $courseXml) {
            $course = new Course();
            $course->setName($courseXml->name);
            $course->setCapacity($courseXml->capacity);
            $course->setSchool($school);
            $this->em->persist($course);
            $this->importCourseRegistrations($courseXml->course_registrations, $course);
        }

        $this->em->flush();

    }


    /**
     * @param \SimpleXMLElement[] $registrationListXML
     * @param Course $course
     */
    private function importCourseRegistrations($registrationListXML, $course)
    {

        foreach ($registrationListXML as $registrationXml) {
            $registration = new Registration();
            $registration->setName($registrationXml->name);
            $registration->setSurname($registrationXml->surname);
            $registration->setCourse($course);
            $this->em->persist($registration);
        }

        $this->em->flush();

    }


    /**
     * @param string $xml
     * @return bool
     */
    private function is_valid_xml($xml)
    {
        libxml_use_internal_errors(true);

        $doc = new DOMDocument('1.0', 'utf-8');

        $doc->loadXML($xml);
        $errors = libxml_get_errors();

        return empty($errors) && $doc->schemaValidate(__DIR__ . "/../Resources/doc/import.xsd");
    }
}