<?php

namespace AppBundle\Controller;

use FOS\UserBundle\Command\CreateUserCommand;
use FOS\UserBundle\Model\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();

        $repoCourse = $em->getRepository('CourseBundle:Course');

        $courses = $repoCourse->findAll();

        return $this->render('AppBundle:Default:index.html.twig', array('courses' => $courses));
    }
}
