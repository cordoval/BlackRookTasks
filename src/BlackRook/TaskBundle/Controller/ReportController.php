<?php

namespace BlackRook\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use BlackRook\TaskBundle\Entity\Report;
use BlackRook\TaskBundle\Form\ReportType;

/**
 * Report controller.
 *
 * @Route("/report")
 */
class ReportController extends Controller
{
    /**
     * Lists all Report entities.
     *
     * @Route("/", name="report")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('BlackRookTaskBundle:Report')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a Report entity.
     *
     * @Route("/{id}/show", name="report_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
		$taskRepository = $em->getRepository('BlackRookTaskBundle:Task');

        $entity = $em->getRepository('BlackRookTaskBundle:Report')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $tasks = array();
        $facts = $entity->getFacts();
        if(is_array($facts)) {
            foreach ($facts as $key => $fact) {

                $fact['params'] = (is_array($fact['params'])) ? $fact['params'] : array($fact['params']);

                call_user_func_array(array($qb, $fact['method']), $fact['params']);

                if(isset($fact['values'])) {
                    foreach ($fact['values'] as $key => $value) {
                        $qb->setParameter($key, $value);
                    }
                }
            }
            $tasks = $qb->getQuery()->getResult();
        }
 
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'tasks' => $tasks,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Report entity.
     *
     * @Route("/new", name="report_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Report();
        $form   = $this->createForm(new ReportType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Report entity.
     *
     * @Route("/create", name="report_create")
     * @Method("post")
     * @Template("BlackRookTaskBundle:Report:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Report();
        $request = $this->getRequest();
        $form    = $this->createForm(new ReportType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('report_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Report entity.
     *
     * @Route("/{id}/edit", name="report_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BlackRookTaskBundle:Report')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $editForm = $this->createForm(new ReportType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Report entity.
     *
     * @Route("/{id}/update", name="report_update")
     * @Method("post")
     * @Template("BlackRookTaskBundle:Report:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BlackRookTaskBundle:Report')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Report entity.');
        }

        $editForm   = $this->createForm(new ReportType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('report_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Report entity.
     *
     * @Route("/{id}/delete", name="report_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('BlackRookTaskBundle:Report')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Report entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('report'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
