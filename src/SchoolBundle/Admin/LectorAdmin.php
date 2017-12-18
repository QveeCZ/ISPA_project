<?php

namespace SchoolBundle\Admin;

use Doctrine\ORM\EntityNotFoundException;
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


    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->add('pdf', 'pdf/' . $this->getRouterIdParameter());
    }

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
            ->with('General',array('label' => 'Informace o lektorovi'))
            ->add('name', null, array('label' => 'Jméno:'))
            ->add('surname', null, array('label' => 'Příjmení:'))
            ->add('email', null, array('label' => 'Email:'))
            ->add('phone', null, array('label' => 'Telefon:'))
            ->add('hodinova_mzda', null, array('label' => 'Mzda:'))
            ->add('pocet_deti', null, array('label' => 'Počet dětí:'))
            ->add('dateMedical' ,null, array('format' => 'd.m.Y','label' => 'Lékařská prohlídka:'))
            ->add('school', null, array('label' => 'Škola:'))
            ->add('filename', 'image', array(
                'prefix' => '/upload/',
                'width' => 250,
                'height' => 250,
            ))
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
            ->with('General', array('label' => 'Lektor'))
            ->add('name', null, array('required' => TRUE, 'label' => 'Jméno:'))
            ->add('surname', null, array('required' => TRUE, 'label' => 'Příjmení:'))
            ->add('email', null, array('required' => TRUE, 'label' => 'Email:'))
            ->add('hodinova_mzda', null, array('required' => TRUE,'label' => 'Mzda:'))
            ->add('pocet_deti', null, array('required' => TRUE,'label' => 'Počet dětí:'))
            ->add('phone', null, array('required' => TRUE, 'label' => 'Telefon:'))
            ->add('dateMedical', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => TRUE, 'label' => 'Datum lékařské prohlídky:'))
            ->add('file', 'file', ['required' => false])
            ->end();


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
         * @var AuthorizationChecker $securityContext
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');

        $filterMapper
            ->add('name', null, array('label' => 'Jméno:'))
            ->add('surname', null, array('label' => 'Příjmení:'))
            ->add('email', null, array('label' => 'Email:'));

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

        $listMapper
            ->add('name', null, array('label' => 'Jméno'))
            ->add('surname', null, array('label' => 'Příjmení'))
            ->addIdentifier('email', null, array('label' => 'Email'))
            ->add('dateMedical' ,null, array('format' => 'd.m.Y','label' => 'Lékařská prohlídka'))
            ->add('hodinova_mzda', null, array('label' => 'Mzda'))
            ->add('pocet_deti', null, array('label' => 'Počet dětí'))
            ->add('school', null, array('label' => 'Škola'))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                    'PDF' => array(
                        'template' => 'SchoolBundle:CRUD:list__action_pdf.html.twig'
                    )
                ),'label' => 'Akce'
            ));
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

        $this->manageFileUpload($lector);

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

    /**
     * @param Lector $lector
     * @throws EntityNotFoundException
     */
    public function preUpdate($lector)
    {
        $this->manageFileUpload($lector);
    }

    /**
     * @param Lector $lector
     * @throws EntityNotFoundException
     */
    private function manageFileUpload($lector)
    {
        if ($lector->getFile()) {
            $lector->upload();
            $lector->refreshUpdated();
        }
    }
}

