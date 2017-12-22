<?php

namespace CourseBundle\Admin;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\Registration;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
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


class RegistrationAdmin extends AbstractAdmin
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

        $query->leftJoin($query->getRootAlias() . '.course', 'c');

        $query->andWhere(
            $query->expr()->eq('c.school', ':school')
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
        if (!$securityContext->isGranted('ROLE_STAFF') && $subject->getCourse()->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }


        $showMapper
            ->with('General',array('label' => 'Informace o uchazeči'))
            ->add('name' ,null,  array('label' => 'Jméno'))
            ->add('surname',null,  array('label' => 'Příjmení'))
            ->add('course',null,  array('label' => 'Kurz'))
            ->add('email',null,  array('label' => 'Email'))
            ->add('birthDate',null,  array('label' => 'Datum narození'))
            ->add('registrationLectures',null,  array('label' => 'Lekce'))
            ->add('registrationRides',null,  array('label' => 'Jízdy'))
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
         * @var Registration $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') && $this->id($this->getSubject()) && $subject->getCourse()->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }

        $formMapper
            ->with('General',array('label' => 'Uchazeč'))
            ->add('name', null, array('required' => true, 'label' => 'Název:'))
            ->add('surname', null, array('required' => true, 'label' => 'Příjmení:'))
            ->add('course', null, array('required' => true, 'label' => 'Kurz:'))
            ->add('email', null, array('required' => true, 'label' => 'Email:'))
            ->add('birthDate', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => TRUE, 'label' => 'Datum narození:'))
            ->add('registrationLectures', 'sonata_type_collection', array('label' => 'Lekce:','required' => false,
                'by_reference' => false,
                'disabled'  => true,
            ), array(
                'edit' => 'standard',
                'sortable' => 'position',
            ))
            ->add('registrationRides', 'sonata_type_collection', array('label' => 'Jízdy:','required' => false,
                'by_reference' => false,
                'disabled'  => true
            ), array(
                'edit' => 'standard',
                'sortable' => 'position',
            ))
            ->end();




        if ($securityContext->isGranted('ROLE_STAFF')) {
            $formMapper
                ->with('General')
                ->add('course', null, array('required' => true, 'label' => 'Kurz:'))
                ->end();
        } else {
            $formMapper
                ->with('General')
                ->add('course', null, array('required' => true, 'label' => 'Kurz:',
                    'class' => 'CourseBundle\Entity\Course',
                    'query_builder' => function ($repository) use ($currentUser) {
                        return $repository->createQueryBuilder('c')
                            ->where('c.school = ' . $currentUser->getSchool()->getId());
                    }))
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
         * @var User $currentUser
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();

        $filterMapper
            ->add('name',null,array('label' => 'Jméno'))
            ->add('surname',null,array('label' => 'Příjmení'))
            ->add('email',null,array('label' => 'Email'))
            ->add('birthDate',null,array('label' => 'Datum narození'));


        if ($securityContext->isGranted('ROLE_STAFF')) {
            $filterMapper
                ->add('course',null,array('label' => 'Kurz'));
        }else{
            $filterMapper
                ->add('course', null, array('label' => 'Kurz:',
                'class' => 'CourseBundle\Entity\Course',
                'query_builder' => function ($repository) use ($currentUser) {
                    return $repository->createQueryBuilder('c')
                        ->where('c.school = ' . $currentUser->getSchool()->getId());
                }));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('name' ,null,  array('label' => 'Jméno'))
            ->add('surname',null,  array('label' => 'Příjmení'))
            ->add('course',null,  array('label' => 'Kurz'))
            ->add('email',null,  array('label' => 'Email'))
            ->add('birthDate',null,  array('format' => 'd.m.Y','label' => 'Datum narození'))
            ->add('registrationLectures',null,  array('label' => 'Lekce'))
            ->add('registrationRides',null,  array('label' => 'Jízdy'))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                ),'label' => 'Akce'
            ));
    }
}

