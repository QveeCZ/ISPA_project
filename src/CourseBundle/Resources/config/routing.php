<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->add('CourseBundle_registration', new Route('/course/register/{courseId}', array(
    '_controller' => 'CourseBundle:Registration:register',
)));

$collection->add('CourseBundle_export_pdf', new Route('/admin/course/course/pdf/{courseId}', array(
    '_controller' => 'CourseBundle:CourseExport:pdf',
)));

return $collection;
