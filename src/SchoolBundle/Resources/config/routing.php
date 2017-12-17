<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();


$collection->add('SchoolBundle_export_pdf', new Route('/admin/school/lector/pdf/{courseId}', array(
    '_controller' => 'SchoolBundle:LectorExport:pdf',
)));

return $collection;
