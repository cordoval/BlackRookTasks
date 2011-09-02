<?php

namespace BlackRook\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use BlackRook\TaskBundle\Entity\Activity;
use BlackRook\TaskBundle\Form\ActivityType;
use Doctrine\Common\Util\Debug as DDebug;

/**
 * Activity controller.
 *
 * @Route("/activity")
 */
class ActivityController extends Controller
{
    /**
     * Lists all Activity entities.
     *
     * @Route("/", name="activity")
     * @Template("BlackRookTaskBundle:Activity:index.html.twig")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $activities = $em->getRepository('BlackRookTaskBundle:Activity')->findAll();

        return array('activities' => $activities);
    }

    /**
     * Finds and displays a Activity entity.
     *
     * @Route("/{id}/show", name="activity_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BlackRookTaskBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Activity entity.
     *
     * @Route("/new", name="activity_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Activity();
        $form   = $this->createForm(new ActivityType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Activity entity.
     *
     * @Route("/create", name="activity_create")
     * @Method("post")
     * @Template("BlackRookTaskBundle:Activity:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Activity();
        $user = $this->get('security.context')->getToken()->getUser();
        $entity->setUser($user);

        $request = $this->getRequest();
        $form    = $this->createForm(new ActivityType(), $entity);
        $form->bindRequest($request);
        
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('activity_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Activity entity.
     *
     * @Route("/{id}/edit", name="activity_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BlackRookTaskBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        $editForm = $this->createForm(new ActivityType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Activity entity.
     *
     * @Route("/{id}/update", name="activity_update")
     * @Method("post")
     * @Template("BlackRookTaskBundle:Activity:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BlackRookTaskBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        $editForm   = $this->createForm(new ActivityType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('activity_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Toggles a activity
     *
     * @Route("/{id}/toggle", name="activity_toggle")
     */
     public function toggleAction($id)
     {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BlackRookTaskBundle:Activity')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Activity entity.');
        }

        $now = new \DateTime("now");
        $started_at = $entity->getStartedAt();
        $time_spent = $entity->getDuration();

        if($started_at) {
            $entity->setStartedAt(null);
            $entity->setDuration($time_spent + date_timestamp_get($now) - date_timestamp_get($started_at));
        } else {
            $entity->setStartedAt($now);
        }
        $em->persist($entity);
        $em->flush();
        return $this->redirect($this->generateUrl('activity_show', array('id' => $id)));
     }

    /**
     * Deletes a Activity entity.
     *
     * @Route("/{id}/delete", name="activity_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('BlackRookTaskBundle:Activity')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Activity entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('activity'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    private function debug($obj, $exit = true) {
        var_dump(DDebug::export($obj, 2));
        if($exit) exit();
    }
}
