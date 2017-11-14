<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->add('ImportXMLSchema_registration', new Route('/import/xml_schema', array(
    '_controller' => 'IOBundle:Default:xmlSchema',
)));

return $collection;
