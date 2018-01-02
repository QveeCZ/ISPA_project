<?php

namespace SchoolBundle\Admin;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use SchoolBundle\Entity\Car;
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


class CarAdmin extends AbstractAdmin
{
    public function createQuery($context = 'list')
    {

        /**
         * @var AuthorizationChecker $securityContext
         * @var User $currentUser
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();

        $query = parent::createQuery($context);

        if($securityContext->isGranted('ROLE_STAFF')){
            return $query;
        }

        $query->andWhere(
            $query->expr()->eq($query->getRootAlias().'.school', ':school')
        );
        $query->setParameter('school', $currentUser->getSchool());
        return $query;
    }


    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        /**
         * @var AuthorizationChecker $securityContext
         * @var User $currentUser
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();

        /**
         * @var Car $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') && $subject->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }


        $showMapper
            ->with('General',array('label' => 'Informace o autě'))
            ->add('spz', null,  array('label' => 'STK'))
            ->add('dateSTK',null, array('format' => 'd.m.Y','label' => 'Datum STK'))
            ->add('school',null, array('label' => 'Škola'))
            ->add('color',null, array('label' => 'Barva'))
            ->add('carType',null,array('label' => 'Typ auta'))
            ->add('fuelConsumption',null,array('label' => 'Spotřeba'))
            ->add('carRides', 'sonata_type_collection', array('label' => 'Jízdy:'))
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /**
         * @var AuthorizationChecker $securityContext
         * @var User $currentUser
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();

        /**
         * @var Car $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') && $this->id($this->getSubject()) && $subject->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }
        /**
         * @var AuthorizationChecker $securityContext
         * @var User $currentUser
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $formMapper
            ->with('General',array('label' => 'Auto'))
            ->add('spz', null, array('required' => TRUE,'label' => 'SPZ:'))
            ->add('color', null, array('required' => TRUE,'label' => 'Barva:'))
            ->add('carType', null, array('required' => TRUE,'label' => 'Typ auta:'))
            ->add('condition', null, array('required' => TRUE,'label' => 'Stav:'))
            ->add('fuelConsumption', null, array('required' => TRUE,'label' => 'Spotřeba:'))
            ->end();

        if ($securityContext->isGranted('ROLE_STAFF')) {
            $formMapper
                ->with('General')
                ->add('school', null, array('required' => TRUE,'label' => 'Škola'))
                ->end();
        }


        $formMapper
            ->with('General')
            ->add('carRides', 'sonata_type_collection', array('label' => 'Jízdy:','required' => false,
                'by_reference' => false,
                'btn_add' => false,
                'disabled'  => true
            ), array(
                'edit' => 'standard',
                'sortable' => 'position',
            ))
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
            ->add('spz',null,array('label' => 'SPZ'));


        if ($securityContext->isGranted('ROLE_STAFF')) {
            $filterMapper
                ->add('school',null, array('label' => 'Škola'));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('spz',null,array('label' => 'SPZ'))
            ->add('dateSTK',null,array('format' => 'd.m.Y','label' => 'Datum STK'))
            ->add('school', null,array('label' => 'Škola'))
            ->add('color',null, array('label' => 'Barva'))
            ->add('carType',null,array('label' => 'Typ auta'))
            ->add('fuelConsumption',null,array('label' => 'Spotřeba'))
            ->add('totalridelength',null,array('label' => 'Najeto'))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array()
                ),'label' => 'Akce'
            ));
    }

    /**
     * @param Car $car
     * @throws EntityNotFoundException
     */

    public function prePersist($car)
    {
        /**
         * @var AuthorizationChecker $securityContext
         * @var User $currentUser
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();


        if($securityContext->isGranted('ROLE_STAFF')){
            parent::preUpdate($car);
            return;
        }

        if(!$currentUser->getSchool()){
            throw new EntityNotFoundException("User " . $currentUser->getId() . " doesnt have ROLE_STAFF and is not associated with any school");
        }

        $car->setSchool($currentUser->getSchool());
        parent::prePersist($car);


    }

    public function getTemplate($name)
    {
        switch ($name) {
            case "edit":
                return $this->getEditTemplate();
                break;
        }
        return parent::getTemplate($name);
    }

    public function getEditTemplate()
    {
        return 'SchoolBundle:Admin:editCar.html.twig';
    }
}

