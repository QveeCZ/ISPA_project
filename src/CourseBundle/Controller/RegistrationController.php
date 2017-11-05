<?php
namespace CourseBundle\Controller;

use CourseBundle\Entity\Registration;
use CourseBundle\Form\Model\CourseRegistration;
use CourseBundle\Form\Type\RegistrationType;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegistrationController extends Controller
{

    public function registerAction($courseId){

        $em = $this->getDoctrine()->getManager();

        $repoCourse = $em->getRepository('CourseBundle:Course');

        $course = $repoCourse->find($courseId);

        if(!$course){
            throw new EntityNotFoundException('Course with id ' . $course . ' not found.');
        }


        $courseRegistrationModel = new CourseRegistration();
        $courseRegistrationType = new RegistrationType();

        $form = $this->createForm($courseRegistrationType, $courseRegistrationModel);

        $request = $this->container->get('request');
        if ($request->getMethod() == 'POST' && isset($_POST["courseRegistrationForm"])) {
            $form->bindRequest($request);

        }



        $template = 'CourseBundle:Form:CourseRegistration.html.twig';
        return $this->render($template, array(
            'form' => $form->createView(),
            'course' => $course,
        ));

    }


}