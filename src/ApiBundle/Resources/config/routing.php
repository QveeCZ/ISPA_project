<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->add('ApiBundle_api', new Route('/api', array(
    '_controller' => 'ApiBundle:Default:api',
)));
return $collection;
