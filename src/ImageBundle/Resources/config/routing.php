<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();


$collection->add('ImageBundle_getLectorFile', new Route('/admin/file/lector/{fileId}', array(
    '_controller' => 'ImageBundle:Default:lectorFile',
)));

$collection->add('ImageBundle_getCarFile', new Route('/admin/file/car/{fileId}', array(
    '_controller' => 'ImageBundle:Default:carFile',
)));


return $collection;
