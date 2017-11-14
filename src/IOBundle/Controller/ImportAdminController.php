<?php

namespace IOBundle\Controller;

use IOBundle\Util\SystemImport;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UserBundle\Entity\User;

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

        if ($this->get('security.authorization_checker')->isGranted('ROLE_STAFF')) {
            $repoSchool = $em->getRepository('SchoolBundle:School');
            $schools = $repoSchool->findAll();
        }

        if ($request->getMethod() == 'POST') {

            /**
             * @var SystemImport $systemImport
             */
            $systemImport = $this->get('system_import');
            $importClass = $systemImport->getImportClass($request->request->get('import_document_type'));

            if (!$importClass) {
                $this->addFlash('sonata_flash_error', 'import_unknown_type');
                return $this->redirect($request->headers->get('referer'));
            }

            $importFile = $request->files->get('import_document');

            if (!$importFile) {
                $this->addFlash('sonata_flash_error', 'import_no_file');
                return $this->redirect($request->headers->get('referer'));
            }

            $repoSchool = $em->getRepository('SchoolBundle:School');

            /**
             * @var AuthorizationChecker $securityContext
             */
            $securityContext = $this->admin->getConfigurationPool()->getContainer()->get('security.authorization_checker');

            if($securityContext->isGranted('ROLE_STAFF')) {
                $school = $repoSchool->find($request->request->get('school'));
            }else{
                /**
                 * @var User $currentUser
                 */
                $currentUser = $this->getConfigurationPool()->getContainer()->get('security.token_storage')->getToken()->getUser();
                $school = $currentUser->getSchool();
            }

            try {
                $importClass->doImport($importFile, $school);
                $this->addFlash('sonata_flash_success', 'import_successful');
            } catch (\Exception $e) {
                $this->addFlash('sonata_flash_error', $e->getMessage());
            }

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render($this->admin->getTemplate('list'), array(
            'action' => 'list',
            "schools" => $schools,
        ));

    }


}