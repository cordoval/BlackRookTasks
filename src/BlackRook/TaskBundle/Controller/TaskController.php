<?php

namespace BlackRook\TaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use BlackRook\TaskBundle\Entity\Task;
use BlackRook\TaskBundle\Form\TaskType;
use BlackRook\TaskBundle\Form\TasksType;
use Doctrine\Common\Util\Debug as DDebug;
use DoctrineExtensions\Paginate\Paginate;

/**
 * Task controller
 * 
 * @Route("/task")
 */
class TaskController extends Controller
{

	/**
	 * Lists all Task entities.
	 *
     * @Route("/", name="tasks")
	 * @Template("BlackRookTaskBundle:Task:index.html.twig")
	 */
	public function getTasksAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$taskRepository = $em->getRepository('BlackRookTaskBundle:Task');

        // $qb = $em->createQueryBuilder();
        // $qb->select('t')
        //     ->from('BlackRook\TaskBundle\Entity\Task', 't');
        // $query = $qb->getQuery();
        // $count = Paginate::getTotalQueryResults($query);
        // $paginateQuery = Paginate::getPaginateQuery($query, 1, 1);
        // $result = $paginateQuery->getResult();
        // self::debug($result);

		return array(

			'tasks' => $taskRepository->getRootNodes(),
			'tasksUncompleted' => $taskRepository->findUncompletedTasks(),

            'tasksCompleted' => $taskRepository->findCompletedTasks(),
            'tasksCompletedInLast7Days' => $taskRepository->findCompletedTasks("-7 days"),
            'tasksCompletedInLast14Days' => $taskRepository->findCompletedTasks("-14 days"),
            'tasksCompletedInLast30Days' => $taskRepository->findCompletedTasks("-30 days"),

            // findUpcomingTasks($to = '', $from = '')
            'tasksUpcomingIn0Days' => $taskRepository->findUpcomingTasks(),
            'tasksUpcomingIn1Days' => $taskRepository->findUpcomingTasks("+1 days"),
            'tasksUpcomingIn7Days' => $taskRepository->findUpcomingTasks("+8 days", "+1 days"),
            'tasksUpcomingIn14Days' => $taskRepository->findUpcomingTasks("+15 days", "+8 days"),
            'tasksUpcomingIn30Days' => $taskRepository->findUpcomingTasks("+30 days", "+15 days"),
 
            // Overdue tasks
            // 'tasksDue-7Days' => $taskRepository->findDueTasks("-7 days", "now"),
            // 'tasksDue-14Days' => $taskRepository->findDueTasks("-14 days"),
            // 'tasksDue-30Days' => $taskRepository->findDueTasks("-30 days"),
            // Tasks with upcoming due dates
            // 'tasksDue7Days' => $taskRepository->findDueTasks("+7 days"),
            // 'tasksDue14Days' => $taskRepository->findDueTasks("+14 days"),
            // 'tasksDue30Days' => $taskRepository->findDueTasks("+30 days"),
		);
	}

	/**
	 * Displays a form to create a new Task entity.
	 * A hypermedia representation that acts as the engine to POST. Typically this is a form that allows the client to POST a new resource.
	 * 
	 * @Template("BlackRookTaskBundle:Task:new.html.twig")
	 */
	public function newTasksAction()
	{
		$entity = new Task();
		$form	= $this->createForm(new TaskType(), $entity);

		return array(
			'entity' => $entity,
			'form'	 => $form->createView()
		);
	}

	/**
	 * Creates a new Task entity.
	 * 
	 * @Template("BlackRookTaskBundle:Task:new.html.twig")
	 */
	public function postTasksAction()
	{
		$entity	 = new Task();
		$request = $this->getRequest();
		$form	 = $this->createForm(new TaskType(), $entity);

		if ('POST' === $request->getMethod()) {
			$form->bindRequest($request);

			if ($form->isValid()) {
				if($entity->getParent()) {
					$entity->setProject($entity->getParent());
				}
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($entity);
				$em->flush();

				$this->get('session')->setFlash('notice', 'Task #'.$entity->getId().' created');
				return $this->redirect($this->generateUrl('get_tasks'));
			}
		}

		return array(
			'entity' => $entity,
			'form'	 => $form->createView()
		);
	}

	/**
	 * Finds and displays a Task entity.
	 * 
     * @Template("BlackRookTaskBundle:Task:show.html.twig")
	 */
	public function getTaskAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();

		$entity = $em->getRepository('BlackRookTaskBundle:Task')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Task entity.');
		}

		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity'	  => $entity,
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Displays a form to edit an existing Task entity.
	 * edit - A hypermedia representation that acts as the engine to PUT. Typically this is a form that allows the client to PUT, or update, an existing resource
	 * 
     * @Template("BlackRookTaskBundle:Task:edit.html.twig")
     */
	public function editTaskAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();

		$entity = $em->getRepository('BlackRookTaskBundle:Task')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Task entity.');
		}

		$editForm = $this->createForm(new TaskType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		return array(
			'entity'	  => $entity,
			'edit_form'	  => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Updates an existing Task entity.
	 * 
     * @Template("BlackRookTaskBundle:Task:edit.html.twig")
	 */
	public function putTaskAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();

		$entity = $em->getRepository('BlackRookTaskBundle:Task')->find($id);

		if (!$entity) {
			throw $this->createNotFoundException('Unable to find Task entity.');
		}

		$editForm	= $this->createForm(new TaskType(), $entity);
		$deleteForm = $this->createDeleteForm($id);

		$request = $this->getRequest();


		if ('PUT' === $request->getMethod()) {
			$editForm->bindRequest($request);

			if ($editForm->isValid()) {
				if($entity->getParent()) {
					$entity->setProject($entity->getParent()->getProject());
				}
				$em->persist($entity);
				$children = $em->getRepository('BlackRookTaskBundle:Task')->children($entity);
				foreach ($children as $child) {
                    $child->setProject($entity->getProject());
                    $em->persist($child);
				}
				$em->flush();
				$this->get('session')->setFlash('notice', 'Task updated');
				return $this->redirect($this->generateUrl('get_tasks'));
			}
		}

		return array(
			'entity'	  => $entity,
			'edit_form'	  => $editForm->createView(),
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Completes a task
	 * 
     * @Template("BlackRookTaskBundle:Task:index.html.twig")
	 */
	public function toggleTaskAction($id){
		$em = $this->getDoctrine()->getEntityManager();
		$task = $em->getRepository('BlackRookTaskBundle:Task')->find($id);
        if (!$task) {
            throw $this->createNotFoundException('Unable to find Task entity.');
        }

		$completed_at = $task->getCompletedAt() ? null : new \DateTime();
		$task->setcompletedAt($completed_at);

		$em->persist($task);
		$em->flush();
		$this->get('session')->setFlash('notice', 'Task updated');

        return $this->redirect($this->generateUrl('get_tasks'));
	}

	/**
	 * Displays a form to confirm Task entity deletion.
	 * remove - A hypermedia representation that acts as the engine to DELETE. Typically this is a form that allows the client to DELETE an existing resource. Commonly a confirmation form.
	 * 
     * @Template("BlackRookTaskBundle:Task:remove.html.twig")
	 */
	public function removeTaskAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$entity = $em->getRepository('BlackRookTaskBundle:Task')->find($id);
		$deleteForm = $this->createDeleteForm($id);
		return array(
			'entity'	  => $entity,
			'delete_form' => $deleteForm->createView(),
		);
	}

	/**
	 * Deletes a Task entity.
	 */
	public function deleteTaskAction($id)
	{
		$form = $this->createDeleteForm($id);
		$request = $this->getRequest();

		if ('DELETE' === $request->getMethod()) {
			$em = $this->getDoctrine()->getEntityManager();
			$entity = $em->getRepository('BlackRookTaskBundle:Task')->find($id);
			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Task entity.');
			}
			$this->get('session')->setFlash('notice', 'Task deleted');
			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('get_tasks'));
	}

	private function createDeleteForm($id)
	{
		return $this->createFormBuilder(array(
    		    'id' => $id, 
		    ))
			->add('id', 'hidden')
			->getForm()
		;
	}

    private function debug($obj, $exit = true) {
        var_dump(DDebug::export($obj, 2));
        if($exit) exit();
    }
}
