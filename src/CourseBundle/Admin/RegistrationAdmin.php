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
            ->with('General')
            ->add('name')
            ->add('surname')
            ->add('course')
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
            ->with('General')
            ->add('name', null, array('required' => true, 'label' => 'Název:'))
            ->add('surname', null, array('required' => true, 'label' => 'Příjmení:'))
            ->add('course', null, array('required' => true, 'label' => 'Kurz:'))
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
            ->add('name')
            ->add('surname');


        if ($securityContext->isGranted('ROLE_STAFF')) {
            $filterMapper
                ->add('course');
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
            ->addIdentifier('id')
            ->add('name')
            ->add('course');
    }
}

