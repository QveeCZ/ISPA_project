<?php

namespace SchoolBundle\Controller;

use CourseBundle\Entity\Registration;
use CourseBundle\Form\Model\CourseRegistration;
use CourseBundle\Form\Type\RegistrationType;
use Doctrine\ORM\EntityNotFoundException;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class LectorExportController extends Controller
{


    /**
     * @param null $courseId
     * @return Response
     */
    public function pdfAction($courseId = null)
    {

        $response = new Response();

        $response->setContent($courseId);

        return $response;
    }


}