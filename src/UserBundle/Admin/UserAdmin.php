<?php

namespace UserBundle\Admin;

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


class UserAdmin extends BaseUserAdmin
{
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

        if (!$securityContext->isGranted('ROLE_ADMIN') && $this->id($this->getSubject()) != $currentUser->getId()) {
            throw new AccessDeniedException();
        }


        $showMapper
            ->with('General')
            ->add('username')
            ->add('email')
            ->end()
        ;
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

        if (!$securityContext->isGranted('ROLE_ADMIN') && $this->id($this->getSubject()) != $currentUser->getId()) {
            throw new AccessDeniedException();
        }

        $formMapper
            ->with('General')
            ->add('username')
            ->add('email')
            ->add('plainPassword', 'text', array('required' => false))
            ->end()// .. more info
        ;


        if (!$this->getSubject()->hasRole('ROLE_STAFF') && !$this->getSubject()->hasRole('ROLE_ADMIN')) {
            $formMapper
                ->with('General')
                ->add('school')
                ->add('secret', null,
                    array(
                        'label' => 'API kód',
                        'read_only' => true
                    )
                )
                ->end();

        }

        if (!$this->getSubject()->hasRole('ROLE_ADMIN') && $securityContext->isGranted('ROLE_ADMIN')) {
            $formMapper
                ->with('Management')
                ->add('roles', 'sonata_security_roles', array(
                    'expanded' => true,
                    'multiple' => true,
                    'required' => false
                ))
                ->add('enabled', null, array('required' => false))
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

        if (!$securityContext->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $filterMapper
            ->add('id')
            ->add('username')
            ->add('email');
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

        if (!$securityContext->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }

        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('enabled', null, array('editable' => true))
            ->add('createdAt');

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('impersonating', 'string', array('template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig'));
        }
    }

    /**
     * @param User $object
     */
    public function prePersist($object)
    {
        if($this->getRequest()->get($this->getIdParameter()) == null){
            $object->setSecret(hash("whirlpool", $this->uniqid));
        }
    }


}

