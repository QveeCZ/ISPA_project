<?php

namespace CourseBundle\Controller;

use CourseBundle\Entity\Registration;
use CourseBundle\Form\Model\CourseRegistration;
use CourseBundle\Form\Type\RegistrationType;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RegistrationController extends Controller
{

    public function registerAction($courseId)
    {

        $em = $this->getDoctrine()->getManager();

        $repoCourse = $em->getRepository('CourseBundle:Course');

        $course = $repoCourse->find($courseId);

        if (!$course) {
            throw new EntityNotFoundException('Course with id ' . $course . ' not found.');
        }


        $courseRegistrationModel = new CourseRegistration();

        $form = $this->createForm('CourseBundle\Form\Type\RegistrationType', $courseRegistrationModel);

        $request = $this->container->get('request_stack')->getCurrentRequest();


        if ($request->isMethod('POST')) {
            $form->submit($request->request->get($form->getName()));
            if ($form->isSubmitted() && $form->isValid()) {
                /**
                 * @var CourseRegistration $object
                 */
                $object = $form->getData();

                $registration = new Registration();

                $registration->setName($object->getName());
                $registration->setSurname($object->getSurname());
                $registration->setCourse($course);

                $em->persist($registration);
                $em->flush();


            }
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }


        $template = 'CourseBundle:form:CourseRegistration.html.twig';
        return $this->render($template, array(
            'form' => $form->createView(),
            'course' => $course,
        ));

    }


}