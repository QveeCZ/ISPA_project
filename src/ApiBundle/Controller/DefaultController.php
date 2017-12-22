<?php

namespace ApiBundle\Controller;

use CourseBundle\Entity\Course;
use FOS\UserBundle\Command\CreateUserCommand;
use FOS\UserBundle\Model\UserManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;

class DefaultController extends Controller
{
    public function apiAction()
    {

        $em = $this->getDoctrine()->getManager();

        return new Response("a");
    }
}
