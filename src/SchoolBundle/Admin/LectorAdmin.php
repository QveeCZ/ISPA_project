<?php

namespace SchoolBundle\Admin;

use SchoolBundle\Entity\Lector;
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


class LectorAdmin extends AbstractAdmin
{
    public function createQuery($context = 'list')
    {

        /**
         * @var AuthorizationChecker $securityContext
         * @var User $currentUser
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();

        /**
         * @var QueryBuilder $query
         */
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
         * @var Lector $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') && $subject->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }

        $showMapper
            ->with('General')
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('phone')
            ->add('dateMedical')
            ->add('school')
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
         * @var Lector $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') && $this->id($this->getSubject()) && $subject->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }

        $formMapper
            ->with('General')
            ->add('name', null, array('required' => TRUE))
            ->add('surname', null, array('required' => TRUE))
            ->add('email', null, array('required' => TRUE))
            ->add('phone', null, array('required' => TRUE))
            ->add('dateMedical', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => TRUE))
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
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('dateMedical');

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
            ->add('name')
            ->add('surname')
            ->add('email')
            ->add('dateMedical')
            ->add('school');
    }
    /**
     * @param Lector $lector
     * @throws EntityNotFoundException
     */
    public function prePersist($lector)
    {
        /**
         * @var AuthorizationChecker $securityContext
         * @var User $currentUser
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();

        if($securityContext->isGranted('ROLE_STAFF')){
            parent::preUpdate($lector);
            return;
        }

        if(!$currentUser->getSchool()){
            throw new EntityNotFoundException("User " . $currentUser->getId() . " doesnt have ROLE_STAFF and is not associated with any school");
        }

        $lector->setSchool($currentUser->getSchool());
        parent::prePersist($lector);


    }
}

