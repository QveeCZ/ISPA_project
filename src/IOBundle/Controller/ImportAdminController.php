<?php

namespace IOBundle\Controller;

use IOBundle\Util\SystemImport;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ImportAdminController extends CRUDController
{
    public function listAction()
    {
        if (false === $this->admin->isGranted('LIST')) {
            throw new AccessDeniedException();
        }


        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $schools = array();

        if ( $this->get('security.authorization_checker')->isGranted('ROLE_STAFF')) {
            $repoSchool = $em->getRepository('SchoolBundle:School');
            $schools = $repoSchool->findAll();
        }

        if ($request->getMethod() == 'POST') {

            /**
             * @var SystemImport $importClass
             */
            $importClass = $this->get('system_import');

            $importClass->getImportClass($request->request->get('import_document_type'));

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render($this->admin->getTemplate('list'), array(
            'action' => 'list',
            "schools" => $schools,
        ));

    }


}