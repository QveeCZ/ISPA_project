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
            ->with('General')
            ->add('spz')
            ->add('dateSTK')
            ->add('school')
            ->add('color')
            ->add('carType')
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
            ->with('General')
            ->add('spz', null, array('required' => TRUE))
            ->add('dateSTK', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => TRUE))
            ->add('color', null, array('required' => TRUE))
            ->add('carType', null, array('required' => TRUE))
            ->add('condition', null, array('required' => TRUE))
            ->end();

        if ($securityContext->isGranted('ROLE_STAFF')) {
            $formMapper
                ->with('General')
                ->add('school', null, array('required' => TRUE))
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
            ->add('spz')
            ->add('dateSTK');


        if ($securityContext->isGranted('ROLE_STAFF')) {
            $filterMapper
                ->add('school');
        }
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
            ->add('color')
            ->add('carType');
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

