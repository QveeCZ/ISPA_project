<?php

namespace ImageBundle\Admin;

use Doctrine\ORM\EntityNotFoundException;
use Doctrine\ORM\QueryBuilder;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Route\RouteCollection;
use ImageBundle\Entity\LectorImage;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use UserBundle\Entity\User;

class LectorImageAdmin extends AbstractAdmin
{

    protected $baseRouteName = 'admin_image_lector_image';
    protected $baseRoutePattern = 'image/lectorimage';


    public function __construct($code, $class, $baseControllerName)
    {
        parent::__construct($code, $class, $baseControllerName);

        if (!$this->hasRequest()) {
            $this->datagridValues = array(
                '_sort_order' => 'DESC', // sort direction
                '_sort_by' => 'protocolDate' // field name
            );
        }
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

        $query->leftJoin($query->getRootAlias() . '.lector', 'l');

        $query->andWhere(
            $query->expr()->eq('l.school', ':school')
        );
        $query->setParameter('school', $currentUser->getSchool());
        return $query;
    }



    protected function configureFormFields(FormMapper $formMapper)
    {
        /**
         * @var AuthorizationChecker $securityContext
         * @var User $currentUser
         */
        $securityContext = $this->getConfigurationPool()->getContainer()->get('security.authorization_checker');
        $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();

        $formMapper
            ->add('file', 'file', array('required' => false, 'label' => 'Nahrát obrázek:'))
            ->add('protocolDate', 'sonata_type_date_picker', array('format' => 'dd.MM.yyyy', 'required' => TRUE, 'label' => 'Datum lékařské prohlídky:'))
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
                    'class' => 'ImageBundle\Entity\LectorImage',
                    'query_builder' => function ($repository) use ($currentUser) {
                        return $repository->createQueryBuilder('l')
                            ->where('l.school = ' . $currentUser->getSchool()->getId());
                    }))
                ->end();
        }
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('lector', null, array('required' => true, 'label' => 'Lektor:'));
    }

    public function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('filename', 'string', array('label' => 'Obrázek'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    //'publish' => array(),
                    'view' => array(),
                    'edit' => array(),
                    'delete' => array()
                )
            , 'label' => 'Akce'));
    }


    /**
     * @param LectorImage $lectorImage
     * @throws EntityNotFoundException
     */
    public function prePersist($lectorImage)
    {
        $this->manageFileUpload($lectorImage);
    }

    /**
     * @param LectorImage $lectorImage
     */
    public function preRemove($lectorImage)
    {
        $lectorImage->remove();
    }

    /**
     * @param LectorImage $lectorImage
     * @throws EntityNotFoundException
     */
    private function manageFileUpload($lectorImage)
    {
        if ($lectorImage->getFile()) {
            $lectorImage->upload();
            $lectorImage->refreshUpdated();
        }
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
        return 'ImageBundle:Admin:editLectorImage.html.twig';
    }

}