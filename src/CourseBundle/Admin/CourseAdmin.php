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


class CourseAdmin extends AbstractAdmin
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

        if ($securityContext->isGranted('ROLE_STAFF')) {
            return $query;
        }

        $query->andWhere(
            $query->expr()->eq($query->getRootAlias() . '.school', ':school')
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
         * @var Course $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') && $subject->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }


        $showMapper
            ->with('General', array('label' => 'Informace o kurzu'))
            ->add('id',null,array('label' => 'ID'))
            ->add('name',null, array('label' => 'Název kurzu'))
            ->add('capacity',null, array('label' => 'Kapacita'))
            ->add('school',null,array('label' => 'Škola'))
            ->add('courseRegistrations',null,array('label' => 'Zaregistrovaní uchazeči'))
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
         * @var Course $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') && $this->id($this->getSubject()) && $subject->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }

        $formMapper
            ->with('General',array('label' => 'Kurz'))
            ->add('name', null, array('required' => true, 'label' => 'Název kurzu:'))
            ->add('capacity', null, array('required' => true, 'label' => 'Kapacita:'))
            ->end();

        if ($securityContext->isGranted('ROLE_STAFF')) {
            $formMapper
                ->with('General')
                ->add('school', null, array('required' => true, 'label' => 'Škola:'))
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
            ->add('id',null,array('label' => 'ID'))
            ->add('name',null,array('label' => 'Název kurzu'))
            ->add('capacity',null,array('label' => 'Kapacita'));


        if ($securityContext->isGranted('ROLE_STAFF')) {
            $filterMapper
                ->add('school',null,array('label' => 'Škola'));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        /**
         * @var AuthorizationChecker $securityContext
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $listMapper
            ->add('id',null,array('label' => 'ID'))
            ->add('name',null,array('label' => 'Název kurzu'))
            ->add('capacity',null,array('label' => 'Kapacita'));




        if ($securityContext->isGranted('ROLE_STAFF')) {
            $listMapper
                ->add('school',null,array('label' => 'Škola'));
        }

        $listMapper
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array()
                ),'label' => 'Akce'
            ));
    }

    /**
     * @param Course $course
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

        if ($securityContext->isGranted('ROLE_STAFF')) {
            parent::preUpdate($course);
            return;
        }

        if (!$currentUser->getSchool()) {
            throw new EntityNotFoundException("User " . $currentUser->getId() . " doesnt have ROLE_STAFF and is not associated with any school");
        }

        $course->setSchool($currentUser->getSchool());
        parent::prePersist($course);


    }
}

