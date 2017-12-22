<?php
/**
 * Created by PhpStorm.
 * User: qvee
 * Date: 22.12.17
 * Time: 11:34
 */

namespace ImageBundle\Controller;


use ImageBundle\Entity\CarImage;
use ImageBundle\Entity\LectorImage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use UserBundle\Entity\User;

class DefaultController extends  Controller
{

    function lectorFileAction($fileId){


        $em = $this->getDoctrine()->getManager();
        $repoFile = $em->getRepository('ImageBundle:LectorImage');

        /**
         * @var LectorImage $file
         */
        $file = $repoFile->find($fileId);

        if(!$file){
            die("Invalid.");
        }



        /**
         * @var AuthorizationChecker $securityContext
         */
        $securityContext = $this->get('security.authorization_checker');

        /**
         * @var User $currentUser
         */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $school = $currentUser->getSchool();

        if(!$securityContext->isGranted('ROLE_STAFF') && $school->getId() != $file->getLector()->getSchool()->getId()) {
            die("Forbidden");
        }


        return $this->returnFile($file->getFilename(), $file->isImage());

    }
    function carFileAction($fileId){


        $em = $this->getDoctrine()->getManager();
        $repoFile = $em->getRepository('ImageBundle:CarImage');

        /**
         * @var CarImage $file
         */
        $file = $repoFile->find($fileId);

        if(!$file){
            die("Invalid.");
        }



        /**
         * @var AuthorizationChecker $securityContext
         */
        $securityContext = $this->get('security.authorization_checker');

        /**
         * @var User $currentUser
         */
        $currentUser = $this->get('security.token_storage')->getToken()->getUser();
        $school = $currentUser->getSchool();

        if(!$securityContext->isGranted('ROLE_STAFF') && $school->getId() != $file->getCar()->getSchool()->getId()) {
            die("Forbidden");
        }


        return $this->returnFile($file->getFilename(), $file->isImage());

    }



    private function returnFile($fileName, $isImage){

        $file =    readfile(\ImageBundle\UPLOAD_DIR . $fileName);


        if($isImage){

            $imageExtension = pathinfo($fileName, PATHINFO_EXTENSION);
            $headers = array(
                'Content-Type'     => 'image/' . $imageExtension,
                'Content-Disposition' => 'inline; filename="'.$fileName.'"');
        }else{

            $headers = array(
                'Content-Disposition' => 'attachment; filename="'.$fileName.'"');
        }

        return new Response($file, 200, $headers);
    }

}