<?php

namespace CourseBundle\Admin;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\Lecture;
use CourseBundle\Entity\Registration;
use CourseBundle\Entity\Ride;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
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
use Sonata\CoreBundle\Validator\ErrorElement;
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
            ->with('General', array('label' => 'Informace o uchazeči'))
            ->add('id', null, array('label' => 'ID:'))
            ->add('name', null, array('label' => 'Jméno'))
            ->add('surname', null, array('label' => 'Příjmení'))
            ->add('course', null, array('label' => 'Kurz'))
            ->add('email', null, array('label' => 'Email'))
            ->add('birthDate', null, array('label' => 'Datum narození'))
            ->add('registrationLectures', null, array('label' => 'Lekce'))
            ->add('registrationRides', null, array('label' => 'Jízdy'))
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
            ->with('General', array('label' => 'Uchazeč'))
            ->add('name', null, array('required' => true, 'label' => 'Název:'))
            ->add('surname', null, array('required' => true, 'label' => 'Příjmení:'))
            ->add('course', null, array('required' => true, 'label' => 'Kurz:'))
            ->add('email', null, array('required' => true, 'label' => 'Email:'))
            ->add('birthDate', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => TRUE, 'label' => 'Datum narození:'))
            ->add('registrationLectures', 'sonata_type_collection', array('label' => 'Lekce:', 'required' => true,
                'by_reference' => true,
                'disabled' => false,
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ))
            ->add('registrationRides', 'sonata_type_collection', array('label' => 'Jízdy:', 'required' => true,
                'by_reference' => false,
                'disabled' => false
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
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
            ->add('name', null, array('label' => 'Jméno'))
            ->add('surname', null, array('label' => 'Příjmení'))
            ->add('email', null, array('label' => 'Email'))
            ->add('birthDate', null, array('label' => 'Datum narození'));


        if ($securityContext->isGranted('ROLE_STAFF')) {
            $filterMapper
                ->add('course', null, array('label' => 'Kurz'));
        } else {
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
            ->add('id', null, array('label' => 'ID:'))
            ->add('name', null, array('label' => 'Jméno'))
            ->add('surname', null, array('label' => 'Příjmení'))
            ->add('course', null, array('label' => 'Kurz'))
            ->add('email', null, array('label' => 'Email'))
            ->add('birthDate', null, array('format' => 'd.m.Y', 'label' => 'Datum narození'))
            ->add('registrationLectures', null, array('label' => 'Lekce'))
            ->add('registrationRides', null, array('label' => 'Jízdy'))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                ), 'label' => 'Akce'
            ));
    }


    /**
     * @param ErrorElement $errorElement
     * @param Registration $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {

        /**
         * @var Connection $connection
         */
        $connection = $this->getConfigurationPool()->getContainer()->get('doctrine')->getConnection();


        //Validace lekci
        $valid = true;
        $error = '';
        $sumLengthPPV = $sumLengthTZBJ = $sumLengthZdrv = 0;

        /**
         * @var Lecture $lecture
         */
        foreach ($object->getRegistrationLectures()->toArray() as $lecture) {


            switch ($lecture->getLectureType()->getName()) {
                case "PPV":
                    $sumLengthPPV = $lecture->getLength();
                    break;
                case "TZBJ":
                    $sumLengthTZBJ = $lecture->getLength();
                    break;
                case "Zdravověda":
                    $sumLengthZdrv = $lecture->getLength();
                    break;
            }
        }


        if ($sumLengthPPV > 30) {
            $valid = false;
            $error .= 'PPV přesahuje 30 hodin.  ';
        }
        if ($sumLengthTZBJ > 15) {
            $valid = false;
            $error .= 'TZBJ přesahuje 15 hodin. ';
        }
        if ($sumLengthZdrv > 3) {
            $valid = false;
            $error .= 'Zdravověda přesahuje 3 hodiny.   ';
        }

        $rides = array();

        /**
         * @var Ride $ride
         */
        foreach ($object->getRegistrationRides()->toArray() as $ride) {



            if (!$ride->getDateRide()) {
                continue;
            }

            if (array_key_exists($ride->getDateRide()->getTimestamp(), $rides) && $rides[$ride->getDateRide()->getTimestamp()] > 1) {
                $error = 'Pro dané datum nelze přidat další jízdu.';
                $errorElement->with('enabled')->addViolation($error)->end();
                $this->getRequest()->getSession()->getFlashBag()->add("menu_type_check", $error);
                break;
            }

            if (array_key_exists($ride->getDateRide()->getTimestamp(), $rides)) {
                $rides[$ride->getDateRide()->getTimestamp()] += 1;
            } else {
                $rides[$ride->getDateRide()->getTimestamp()] = 1;
            }


        }

        if ($object->getRegistrationRides()->count() > 28) {
            $error = 'Pro danou registraci již nelze přidat další jízdu.';
            $errorElement->with('enabled')->addViolation($error)->end();
            $this->getRequest()->getSession()->getFlashBag()->add("menu_type_check", $error);
        }


        if (!$valid) {
            $errorElement->with('enabled')->addViolation($error)->end();
            $this->getRequest()->getSession()->getFlashBag()->add("menu_type_check", $error);
        }
    }
}

