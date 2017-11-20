<?php


use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();

$collection->add('AppBundle_homepage', new Route('/', array(
    '_controller' => 'AppBundle:Default:index',
)));
$collection->add('AppBundle_content', new Route('/{content}', array(
    '_controller' => 'AppBundle:Default:content',
)));

return $collection;
