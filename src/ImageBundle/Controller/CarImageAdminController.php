<?php

namespace ImageBundle\Controller;

use Sonata\AdminBundle\Datagrid\ProxyQueryInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CarImageAdminController extends Controller
{
    public function createAction()
    {
        if (false === $this->admin->isGranted('CREATE')) {
            throw new AccessDeniedException();
        }

        $object = $this->admin->getNewInstance();
        if ($this->get('request')->query->get('car')) {
            $gallery_id = $this->get('request')->query->get('car');
            $gallery = $this->getDoctrine()
                ->getRepository('SchoolBundle:Car')
                ->find($gallery_id);
            $object->setGallery($gallery);
        }

        $this->admin->setSubject($object);

        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->submit($this->get('request'));

            if ($form->isValid()) {
                $this->admin->create($object);

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result' => 'ok',
                        'objectId' => $this->admin->getNormalizedIdentifier($object)
                    ));
                }

                $this->addFlash('sonata_flash_success', 'flash_create_success');
                // redirect to edit mode
                return $this->redirect($this->generateUrl('admin_school_car_edit', array('id' => $object->getCar()->getId())));
            }
            $this->addFlash('sonata_flash_error', 'flash_create_error');
        }

        $view = $form->createView();
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate("edit"), array(
            'action' => 'create',
            'form' => $view,
            'object' => $object,
        ));
    }

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException|\Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @param $id
     * @return Response
     */
    public function deleteAction($id)
    {
        if (false === $this->admin->isGranted('DELETE')) {
            throw new AccessDeniedException();
        }

        $id = $this->get('request')->get($this->admin->getIdParameter());
        $object = $this->admin->getObject($id);
        $car_id = $object->getCar()->getId();

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        try {
            $this->admin->delete($object);
            $this->addFlash('sonata_flash_success', 'flash_delete_success');
        } catch (\Exception $e) {
            $this->addFlash('sonata_flash_error', 'flash_delete_error');
        }

        return $this->redirect($this->generateUrl('admin_school_car_edit', array('id' => $car_id)));
        //}

        /*return $this->render('SonataAdminBundle:CRUD:delete.html.twig', array(
            'object' => $object,
            'action' => 'delete'
        ));*/
    }

    /**
     * return the Response object associated to the edit action
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @param  $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        if (false === $this->admin->isGranted('EDIT')) {
            throw new AccessDeniedException();
        }

        $id = $this->get('request')->get($this->admin->getIdParameter());

        $em = $this->getDoctrine()->getManager();
        $repoGallery = $em->getRepository('SchoolBundle:Car');

        $object = $this->admin->getObject($id);

        if (!$object) {
            throw new NotFoundHttpException(sprintf('unable to find the object with id : %s', $id));
        }

        $this->admin->setSubject($object);

        $form = $this->admin->getForm();
        $form->setData($object);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->submit($this->get('request'));

            if ($form->isValid()) {


                $this->admin->update($object);
                $this->addFlash('sonata_flash_success', 'flash_edit_success');

                if ($this->isXmlHttpRequest()) {
                    return $this->renderJson(array(
                        'result' => 'ok',
                        'objectId' => $this->admin->getNormalizedIdentifier($object)
                    ));
                }

                // redirect to edit mode
                return $this->redirect($this->generateUrl('admin_school_car_edit', array('id' => $object->getGallery()->getId())));
            }

            $this->addFlash('sonata_flash_error', 'flash_edit_error');
        }

        $view = $form->createView();
        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render($this->admin->getTemplate("edit"), array(
            'action' => 'edit',
            'form' => $view,
            'object' => $object,
        ));
    }


}