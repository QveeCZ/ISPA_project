<?php

namespace IOBundle\Util;


use CourseBundle\Entity\Course;
use CourseBundle\Entity\Lecture;
use CourseBundle\Entity\Registration;
use CourseBundle\Entity\Ride;
use CourseBundle\Form\Model\CourseRegistration;
use DateTime;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use DOMDocument;
use ImageBundle\Entity\CarImage;
use ImageBundle\Entity\LectorImage;
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

            $carProtocol = new CarImage();
            $carProtocol->setCar($car);
            $carProtocol->setProtocolDate(DateTime::createFromFormat('Y-m-d', $carXml->date_stk));
            $car->addCarImages($carProtocol);

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

            $lectorProtocol = new LectorImage();
            $lectorProtocol->setLector($lector);
            $lectorProtocol->setProtocolDate(DateTime::createFromFormat('Y-m-d', $lectorXml->date_medical));
            $lector->addCarImages($lectorProtocol);

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
            $this->importRegistrationLectures($registrationXml->registraton_lecture, $registration);
            $this->importRegistrationRides($registrationXml->registraton_ride, $registration);
            $this->em->persist($registration);
        }

        $this->em->flush();

    }

    /**
     * @param \SimpleXMLElement[] $ridesListXML
     * @param Registration $registration
     */
    private function importRegistrationRides($ridesListXML, $registration){

        foreach ($ridesListXML as $rideXML) {
            $ride = new Ride();

            $lector = $this->em->getRepository("SchoolBundle:Lector")->findOneBy(array('email' => $rideXML->lector_email));
            $car = $this->em->getRepository("SchoolBundle:Car")->findOneBy(array('spz' => $rideXML->car_spz));

            $ridesFromDate = $this->em->getRepository("CourseBundle:Ride")->findBy(array('courseRegistration' => $registration->getId(), 'dateRide' => DateTime::createFromFormat('Y-m-d', $rideXML->date_ride)));

            if(!$lector || !$car || count($ridesFromDate) > 1){
                continue;
            }

            $ride->setCar($car);
            $ride->setLector($lector);
            $ride->setDateRide(DateTime::createFromFormat('Y-m-d', $rideXML->date_ride));

            $this->em->persist($ride);
        }

        $this->em->flush();
    }
    /**
     * @param \SimpleXMLElement[] $lectureListXML
     * @param Registration $registration
     */
    private function importRegistrationLectures($lectureListXML, $registration){

        foreach ($lectureListXML as $lectureXML) {
            $lecture = new Lecture();

            $lector = $this->em->getRepository("SchoolBundle:Lector")->findOneBy(array('email' => $lectureXML->lector_email));
            /**
             * @var Connection $connection
             */
            $connection = $this->em->getConnection();
            $query = 'SELECT SUM(length) as sumLength FROM lectures WHERE lecture_type_id = :ltid AND course_registration_id = :crid';
            $stmt = $connection->prepare($query);
            $stmt->bindValue("ltid", $lectureXML->lecture_type);
            $stmt->bindValue("crid", $registration->getId());
            $stmt->execute();
            $sumLength = $stmt->fetch()['sumLength'] + $lectureXML->lecture_length;
            $valid = false;
            switch($lectureXML->lecture_type){
                case "PPV":
                    $valid = ($sumLength > 30) ? false : true;
                    break;
                case "TZBJ":
                    $valid = ($sumLength > 15) ? false : true;
                    break;
                case "Zdravověda":
                    $valid = ($sumLength > 3) ? false : true;
                    break;
            }

            if(!$lector || !$valid){
                continue;
            }

            $lecture->setLector($lector);
            $lecture->setDateLecture(DateTime::createFromFormat('Y-m-d', $lectureXML->date_lecture));
            $lecture->setLength(intval($lectureXML->lecture_length));

            $lectureType = $this->em->getRepository("CourseBundle:LectureType")->find($lectureXML->lecture_type);

            $lecture->setLectureType($lectureType);
            $lecture->setCourseRegistration($registration);

            $this->em->persist($lecture);
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