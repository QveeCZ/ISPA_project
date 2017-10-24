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

//        $om      = $this->container->get('doctrine.orm.entity_manager');
//        $manager = $this->container->get('fos_user.user_manager');
//
//        $user = $manager->createUser();
//
//        /**
//         * @var User $user
//         */
//
//        $user
//            ->setUsername('admin')
//            ->setEmail('inrpo.ispa@gmail.com')
//            ->setPlainPassword('admin')
//            ->setEnabled(true)
//            ->setRoles(array("ROLE_ADMIN"));
//        ;
//
//        $om->persist($user);
//        $om->flush();

        // replace this example code with whatever you need
        return $this->render('AppBundle:Default:index.html.twig', array());
    }
}
