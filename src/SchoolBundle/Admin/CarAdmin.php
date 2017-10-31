<?php

namespace SchoolBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use UserBundle\Entity\User;


class CarAdmin extends BaseUserAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {


        $showMapper
            ->with('General')
            ->add('spz')
            ->add('dateSTK')
            ->add('school')
            ->add('color')
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->with('General')
            ->add('spz', null, array('required' => TRUE))
            ->add('dateSTK', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => TRUE))
            ->add('school', null, array('required' => TRUE))
            ->add('color', null, array('required' => TRUE))
            ->add('condition', null, array('required' => TRUE))
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {

        $filterMapper
            ->add('spz')
            ->add('dateSTK')
            ->add('school')
            ->add('color');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->addIdentifier('spz')
            ->add('dateSTK')
            ->add('school')
            ->add('color');
    }
}

