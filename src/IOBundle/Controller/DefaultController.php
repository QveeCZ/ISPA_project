<?php

namespace IOBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{

    public function xmlSchemaAction(){

        $response = new Response();
        $response->headers->set('Content-Type', 'xml');

        $xsd = file_get_contents(__DIR__ . "/../Resources/doc/import.xsd");

        $response->setContent($xsd);

        return $response;

    }

}