<?php

namespace SchoolBundle\Admin;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\EntityRepository;
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
            ->with('General', array('label' => 'Informace o lektorovi'))
            ->add('name', null, array('label' => 'Jméno:'))
            ->add('surname', null, array('label' => 'Příjmení:'))
            ->add('email', null, array('label' => 'Email:'))
            ->add('phone', null, array('label' => 'Telefon:'))
            ->add('pocet_deti', null, array('label' => 'Počet dětí:'))
            ->add('birthDate', null, array('required' => TRUE, 'format' => 'd.m.Y', 'label' => 'Datum narození:'))
            ->add('dateMedical', 'date', array('format' => 'd.m.Y', 'label' => 'Osvědčení:'))
            ->add('school', null, array('label' => 'Škola:'))
            ->add('lectorRides', 'sonata_type_collection', array('label' => 'Jízdy:'))
            ->add('lectorLectures', 'sonata_type_collection', array('label' => 'Lekce:'))
            ->end();


        if ($securityContext->isGranted('ROLE_SALARY')) {
            $showMapper
                ->add('hodinova_mzda', null, array('required' => TRUE, 'label' => 'Hodinová mzda:'))
                ->end();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        /**
         * @var AuthorizationChecker $securityCo
         * ntext
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
            ->with('General', array('label' => 'Lektor'))
            ->add('name', null, array('required' => TRUE, 'label' => 'Jméno:'))
            ->add('surname', null, array('required' => TRUE, 'label' => 'Příjmení:'))
            ->add('email', null, array('required' => TRUE, 'label' => 'Email:'))
            ->add('pocet_deti', null, array('required' => TRUE, 'label' => 'Počet dětí:'))
            ->add('birthDate', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => TRUE, 'label' => 'Datum narození:'))
            ->add('phone', null, array('required' => TRUE, 'label' => 'Telefon:'))
            ->end();


        if ($securityContext->isGranted('ROLE_SALARY')) {
            $formMapper
                ->add('hodinova_mzda', null, array('required' => TRUE, 'label' => 'Hodinová mzda:'))
                ->end();
        }

        if ($securityContext->isGranted('ROLE_STAFF')) {
            $formMapper
                ->with('General')
                ->add('school', null, array('required' => TRUE, 'label' => 'Škola:'))
                ->end();
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {

        /**
         * @var EntityRepository $repository ;
         */
        $repository = $this->modelManager->getEntityManager($this->getClass())->getRepository($this->getClass());

        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine.orm.entity_manager');
        /**
         * @var Lector[] $lectors
         */
        $lectors = $repository->findAll();
        foreach ($lectors as $lector) {
            $lector->isExpired();
            $em->persist($lector);
            $em->flush();
        }

        /**
         * @var AuthorizationChecker $securityContext
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $filterMapper
            ->add('name', null, array('label' => 'Jméno:'))
            ->add('surname', null, array('label' => 'Příjmení:'))
            ->add('birthDate', null, array('required' => TRUE, 'label' => 'Datum narození:'))
            ->add('email', null, array('label' => 'Email:'))
            ->add('expired', 'doctrine_orm_boolean', array('label' => 'Propadlé oprávnění'));

        if ($securityContext->isGranted('ROLE_STAFF')) {
            $filterMapper
                ->add('school', null, array('label' => 'Škola:'));
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
            ->add('name', null, array('label' => 'Jméno'))
            ->add('surname', null, array('label' => 'Příjmení'))
            ->addIdentifier('email', null, array('label' => 'Email'))
            ->add('dateMedical', 'date', array('format' => 'd.m.Y', 'label' => 'Osvědčení'))
            ->add('pocet_deti', null, array('label' => 'Počet dětí'))
            ->add('birthDate', null, array('format' => 'd.m.Y', 'label' => 'Datum narození:'));




        if ($securityContext->isGranted('ROLE_SALARY')) {
            $listMapper
                ->add('hodinova_mzda', null, array('required' => TRUE, 'label' => 'Hodinová mzda:'));
        }


        $listMapper
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                ),'label' => 'Akce'
            ));
    }

    /**
     * @param Lector $car
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


        if ($securityContext->isGranted('ROLE_STAFF')) {
            parent::preUpdate($car);
            return;
        }

        if (!$currentUser->getSchool()) {
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
        return 'SchoolBundle:Admin:editLector.html.twig';
    }


}

