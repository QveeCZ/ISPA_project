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
                $registration->setEmail($object->getEmail());
                $registration->setBirthDate($object->getBirthDate());

                $em->persist($registration);
                $em->flush();
                $em->refresh($registration);
                $this->confirmationEmail($registration);


            }

            $flashbag = $this->get('session')->getFlashBag();
            $flashbag->add("success", "Registrace proběhla úspěšně.");
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }


        $template = 'CourseBundle:form:CourseRegistration.html.twig';
        return $this->render($template, array(
            'form' => $form->createView(),
            'course' => $course,
        ));

    }

    /**
     * @param Registration $registration
     */
    private function confirmationEmail($registration){

        $confirmLink = md5($registration->getId() . $registration->getCreated()->format('Y-m-d H:i:s'));

//        $mailer = $this->get('mailer');
//        $message = (new \Swift_Message('Potvrzení registrace'))
//            ->setFrom('inpro.ispa@gmail.com')
//            ->setTo($registration->getEmail())
//            ->setBody(
//                $this->renderView(
//                    'CourseBundle:system:CourseRegistrationEmail.html.twig',
//                    array('registration' => $registration, 'confirmLink' => $confirmLink)
//                ),
//                'text/html'
//            )
//        ;

//        $mailer->send($message);
    }


}