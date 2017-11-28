<?php

namespace CourseBundle\Admin;

use CourseBundle\Entity\Course;
use Doctrine\ORM\EntityNotFoundException;
use Sonata\AdminBundle\Admin\AbstractAdmin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use UserBundle\Entity\User;


class RideAdmin extends AbstractAdmin
{


    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {


        $showMapper
            ->with('General')
            ->add('dateRide')
            ->add('lectureType')
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->with('General')
            ->add('dateRide', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => true, 'label' => 'Datum:'))
            ->add('courseRegistration', null, array('required' => true, 'label' => ' ', 'attr' => array('class' => "fa-force-hidden")))
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        /**
         * @var AuthorizationChecker $securityContext
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $filterMapper
            ->add('dateRide')
            ->add('courseRegistration');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('dateRide')
            ->add('courseRegistration')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'delete' => array(),
                )
            ));
    }
}

