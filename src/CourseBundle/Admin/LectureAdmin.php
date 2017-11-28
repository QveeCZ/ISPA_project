<?php

namespace CourseBundle\Admin;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\Lecture;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityNotFoundException;
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


    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {


        $showMapper
            ->with('General')
            ->add('length')
            ->add('lectureType')
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            ->with('General')
            ->add('length', null, array('required' => true, 'label' => 'Délka:'))
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
            ->add('length')
            ->add('lectureType');
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {

        $listMapper
            ->add('length')
            ->add('lectureType')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'delete' => array(),
                )
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

