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


class LectorAdmin extends BaseUserAdmin
{
    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {


        $showMapper
            ->with('General')
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('dateMedical')
            ->add('school')
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->with('General')
            ->add('name', null, array('required' => TRUE))
            ->add('surname', null, array('required' => TRUE))
            ->add('email', null, array('required' => TRUE))
            ->add('dateMedical', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => TRUE))
            ->add('phone', null, array('required' => TRUE))
            ->add('school', null, array('required' => TRUE))
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {

        $filterMapper
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('dateMedical')
            ->add('school');

    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('dateMedical')
            ->add('school');
    }
}

