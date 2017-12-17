<?php

namespace CourseBundle\Admin;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\Lecture;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityNotFoundException;
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


class LectureAdmin extends AbstractAdmin
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

        $query->leftJoin($query->getRootAlias() . '.courseRegistration.course', 'c');

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
         * @var Lecture $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') && $subject->getCourseRegistration()->getCourse()->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }


        $showMapper
            ->with('General',array('label' => 'Informace o lekci'))
            ->add('length',null,  array('label' => 'Délka lekce'))
            ->add('lectureType',null,  array('label' => 'Typ lekce'))
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
         * @var Lecture $subject
         */
        $subject = $this->getSubject();
        if (!$securityContext->isGranted('ROLE_STAFF') && $subject->getCourseRegistration()->getCourse()->getSchool()->getId() != $currentUser->getSchool()->getId()) {
            throw new AccessDeniedException();
        }

        $formMapper
            ->with('General',array('label' => 'Přidání lekce'))
            ->add('length', null, array('required' => true, 'label' => 'Délka lekce:'))
            ->add('lectureType', null, array('required' => true, 'label' => 'Typ lekce:'))
            ->add('courseRegistration', null, array('required' => true, 'label' => ' ', 'attr' => array('class' => "fa-force-hidden")))
            ->end();
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
            ->add('length',null,  array('label' => 'Délka lekce'))
            ->add('lectureType',null,  array('label' => 'Typ lekce'));
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('length',null,  array('label' => 'Délka lekce'))
            ->add('lectureType',null,  array('label' => 'Typ lekce'))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'delete' => array(),
                ),'label' => 'Akce'
            ));
    }

    /**
     * @param ErrorElement $errorElement
     * @param Lecture $object
     */
    public function validate(ErrorElement $errorElement, $object)
    {


        /**
         * @var Connection $connection
         */
        $connection = $this->getConfigurationPool()->getContainer()->get('doctrine')->getConnection();
        $query = 'SELECT SUM(length) as sumLength FROM lectures WHERE lecture_type_id = :ltid AND course_registration_id = :crid';
        $stmt = $connection->prepare($query);
        $stmt->bindValue("ltid", $object->getLectureType()->getName());
        $stmt->bindValue("crid", $object->getCourseRegistration()->getId());
        $stmt->execute();
        $sumLength = $stmt->fetch()['sumLength'] + $object->getLength();
        $valid = false;

        switch($object->getLectureType()->getName()){
            case "PPV":
                $valid = ($sumLength > 30) ? false : true;
                break;
            case "TZBJ":
                $valid = ($sumLength > 15) ? false : true;
                break;
            case "ZDRV":
                $valid = ($sumLength > 3) ? false : true;
                break;
        }


        if(!$valid){
            $error = 'Pro danou registraci nelze přidat další lekci.';
            $errorElement->with( 'enabled' )->addViolation($error)->end();
            $this->getRequest()->getSession()->getFlashBag()->add( "menu_type_check", $error );
        }
    }


}

