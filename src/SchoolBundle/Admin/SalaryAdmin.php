<?php

namespace SchoolBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;

class SalaryAdmin extends AbstractAdmin
{

    protected $baseRouteName = 'salary';
    protected $baseRoutePattern = 'salary';

    public function getTemplate($name)
    {

        switch ($name){
            case "list":
                return 'SchoolBundle:Admin:salary_form.html.twig';
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