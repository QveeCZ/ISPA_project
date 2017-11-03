<?php

namespace SchoolBundle\Admin;

use Doctrine\ORM\EntityNotFoundException;
use SchoolBundle\Entity\Car;
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

    /**
     * @param Car $car
     * @throws EntityNotFoundException
     */
    public function preUpdate($car)
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
        parent::preUpdate($car);


    }


}

