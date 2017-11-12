<?php

namespace IOBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;

class ImportAdmin extends AbstractAdmin
{

    protected $baseRouteName = 'import';
    protected $baseRoutePattern = 'import';

    public function getTemplate($name)
    {

        switch ($name){
            case "list":
                return 'IOBundle:Admin:full_import.html.twig';
                break;
        }

        return parent::getTemplate($name);
    }

    /**
     * Returns the url defined by the $name
     *
     * @param string $name
     *
     * @return \Symfony\Component\Routing\Route
     */
    function getRoute($name)
    {
    }
}