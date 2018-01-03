<?php

namespace CourseBundle\Admin;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\Ride;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\Query\ResultSetMapping;
use Sonata\AdminBundle\Admin\AbstractAdmin;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use FOS\UserBundle\Model\UserManagerInterface;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use UserBundle\Entity\User;


class RideAdmin extends AbstractAdmin
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

        $query->leftJoin($query->getRootAlias() . '.courseRegistration', 'cr');
        $query->leftJoin('cr.course', 'c');

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
         * @var Ride $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') && $subject && $subject->getCourseRegistration()->getCourse()->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }


        $showMapper
            ->with('General',array('label' => 'Informace o jízdě'))
            ->add('dateRide',null,array('format' => 'd.m.Y','label' => 'Datum jízdy'))
            ->add('lectureType',null,array('label' => 'Lekce'))
            ->add('lector', null, array('label' => 'Lektor:'))
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
         * @var Ride $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') &&  $subject && $subject->getCourseRegistration()->getCourse()->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }

        $formMapper
            ->with('General',array('label' => 'Jízda'))
            ->add('dateRide', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => true, 'label' => 'Datum:'))
            ->add('courseRegistration', null, array('required' => true, 'label' => ' ', 'attr' => array('class' => "fa-force-hidden")))
            ->add('length',null, array('label' => 'Délka'))
            ->add('consumption',null, array('label' => 'Litry benzínu'))
            ->end();

        if ($securityContext->isGranted('ROLE_STAFF')) {
            $formMapper
                ->with('General')
                ->add('lector', null, array('required' => true, 'label' => 'Lektor:'))
                ->end();
        } else {
            $formMapper
                ->with('General')
                ->add('lector', null, array('required' => true, 'label' => 'Lektor:',
                    'class' => 'SchoolBundle\Entity\Lector',
                    'query_builder' => function ($repository) use ($currentUser) {
                        return $repository->createQueryBuilder('l')
                            ->where('l.school = ' . $currentUser->getSchool()->getId());
                    }))
                ->end();
        }

        if ($securityContext->isGranted('ROLE_STAFF')) {
            $formMapper
                ->with('General')
                ->add('car', null, array('required' => true, 'label' => 'Auto:'))
                ->end();
        } else {
            $formMapper
                ->with('General')
                ->add('car', null, array('required' => true, 'label' => 'Auto:',
                    'class' => 'SchoolBundle\Entity\Car',
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
            ->add('dateRide',null,array('label' => 'Datum jízdy'))
            ->add('courseRegistration',null,array('label' => 'Informace o uchazeči'));


        if ($securityContext->isGranted('ROLE_STAFF')) {
            $filterMapper
                ->add('lector', null, array('label' => 'Lektor:'));
        } else {
            $filterMapper
                ->add('lector', null, array('label' => 'Lektor:',
                    'class' => 'CourseBundle\Entity\Course',
                    'query_builder' => function ($repository) use ($currentUser) {
                        return $repository->createQueryBuilder('l')
                            ->where('l.school = ' . $currentUser->getSchool()->getId());
                    }));
        }

    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('dateRide',null,array('format' => 'd.m.Y','label' => 'Datum jízdy'))
            ->add('courseRegistration',null,array('label' => 'Informace o uchazeči'))
            ->add('lector', null, array('label' => 'Lektor:'))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'delete' => array(),
                ),'label' => 'Akce'
            ));
    }

    public function validate(ErrorElement $errorElement, $object)
    {

        /**
         * @var Connection $connection
         */
        $connection = $this->getConfigurationPool()->getContainer()->get('doctrine')->getConnection();
        $query = 'SELECT * FROM course_ride WHERE date_ride = :date AND course_registration_id = :id';
        $stmt = $connection->prepare($query);
        $stmt->bindValue("date", $object->getDateRide()->format("Y-m-d"));
        $stmt->bindValue("id", $object->getCourseRegistration()->getId());
        $stmt->execute();
        $rides = $stmt->fetchAll();

        if(count($rides) > 1){
            $error = 'Pro dané datum nelze přidat další jízdu.';
            $errorElement->with( 'enabled' )->addViolation($error)->end();
            $this->getRequest()->getSession()->getFlashBag()->add( "menu_type_check", $error );
        }

        $query = 'SELECT * FROM course_ride WHERE course_registration_id = :id';
        $stmt = $connection->prepare($query);
        $stmt->bindValue("id", $object->getCourseRegistration()->getId());
        $stmt->execute();
        $ridesSum = $stmt->fetchAll();

        if(count($ridesSum) + 1 > 28){
            $error = 'Pro danou registraci již nelze přidat další jízdu.';
            $errorElement->with( 'enabled' )->addViolation($error)->end();
            $this->getRequest()->getSession()->getFlashBag()->add( "menu_type_check", $error );
        }
    }


}

