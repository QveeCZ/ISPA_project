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
            ->add('dateSTK', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => TRUE,'label' => 'Datum STK:'))
            ->add('color', null, array('required' => TRUE,'label' => 'Barva:'))
            ->add('carType', null, array('required' => TRUE,'label' => 'Typ auta:'))
            ->add('condition', null, array('required' => TRUE,'label' => 'Stav:'))
            ->end();

        if ($securityContext->isGranted('ROLE_STAFF')) {
            $formMapper
                ->with('General')
                ->add('school', null, array('required' => TRUE,'label' => 'Škola'))
                ->end();
        }
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
            ->add('spz',null,array('label' => 'SPZ'))
            ->add('dateSTK',null, array('label' => 'Datum STK'));


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
            ->addIdentifier('spz',null,array('label' => 'SPZ'))
            ->add('dateSTK',null,array('format' => 'd.m.Y','label' => 'Datum STK'))
            ->add('school', null,array('label' => 'Škola'))
            ->add('color',null, array('label' => 'Barva'))
            ->add('carType',null,array('label' => 'Typ auta'));
    }

    /**
     * @param Car $course
     * @throws EntityNotFoundException
     */
    public function prePersist($course)
    {
        /**
         * @var AuthorizationChecker $securityContext
         * @var User $currentUser
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();

        if($securityContext->isGranted('ROLE_STAFF')){
            parent::preUpdate($course);
            return;
        }

        if(!$currentUser->getSchool()){
            throw new EntityNotFoundException("User " . $currentUser->getId() . " doesnt have ROLE_STAFF and is not associated with any school");
        }

        $course->setSchool($currentUser->getSchool());
        parent::prePersist($course);


    }


}

