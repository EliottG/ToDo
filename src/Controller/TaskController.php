<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Class TaskController
 * @package App\Controller
 * @IsGranted("ROLE_USER")
 */
class TaskController extends AbstractController
{
	/**
	 * @Route("/tasks", name="task_list")
	 * @param Security $security
	 * @param TaskRepository $repository
	 * @return Response
	 */
	public function listAction(Security $security, TaskRepository $repository)
	{
		if ($security->isGranted('ROLE_ADMIN')) {
			$task = $repository->findByUserAnonymous($this->getUser());
		} else {
			$task = $repository->findAllByUser($this->getUser());
		}
		return $this->render('task/list.html.twig', ['tasks' => $task]);
	}

	/**
	 * @Route("/tasks/create", name="task_create")
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return RedirectResponse|Response
	 * @throws \Exception
	 */
	public function createAction(Request $request, EntityManagerInterface $entityManager)
	{
		$task = new Task();
		$form = $this->createForm(TaskType::class, $task);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$user = $this->getUser();
			if (!$user instanceof User) {
				throw new \Exception('Invalid User');
			}
			$task->setUser($user);
			$entityManager->persist($task);
			$entityManager->flush();
			$this->addFlash('success', 'La tâche a été bien été ajoutée.');
			return $this->redirectToRoute('task_list');
		}

		return $this->render('task/create.html.twig', ['form' => $form->createView()]);
	}

	/**
	 * @Route("/tasks/{id}/edit", name="task_edit")
	 * @param Task $task
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return RedirectResponse|Response
	 * @IsGranted("edit", subject="task")
	 */
	public function editAction(
		Task $task,
		Request $request,
		EntityManagerInterface $entityManager
	) {
		$form = $this->createForm(TaskType::class, $task);

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->flush();

			$this->addFlash('success', 'La tâche a bien été modifiée.');

			return $this->redirectToRoute('task_list');
		}

		return $this->render('task/edit.html.twig', [
			'form' => $form->createView(),
			'task' => $task
		]);
	}

	/**
	 * @Route("/tasks/{id}/toggle", name="task_toggle")
	 * @param Task $task
	 * @param EntityManagerInterface $entityManager
	 * @return RedirectResponse
	 * @IsGranted("edit", subject="task")
	 */
	public function toggleTaskAction(Task $task, EntityManagerInterface $entityManager)
	{
		$task->toggle(!$task->isDone());
		$entityManager->flush();

		$this->addFlash(
			'success',
			sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle())
		);

		return $this->redirectToRoute('task_list');
	}

	/**
	 * @param TaskRepository $taskRepository
	 * @Route("/tasks/done", name="task_done")
	 */
	public function isDoneTaskAction(TaskRepository $taskRepository)
	{
		$user = $this->getUser();
		$tasks = $taskRepository->findByIsDone($user);
		return $this->render('task/listDone.html.twig', [
			'tasks' => $tasks
		]);
	}

	/**
	 * @Route("/tasks/{id}/delete", name="task_delete")
	 * @IsGranted("delete", subject="task")
	 * @param Task $task
	 * @param EntityManagerInterface $entityManager
	 * @return RedirectResponse
	 */
	public function deleteTaskAction(Task $task, EntityManagerInterface $entityManager)
	{
		$em = $this->getDoctrine()->getManager();
		$entityManager->remove($task);
		$entityManager->flush();
		$this->addFlash('success', 'La tâche a bien été supprimée.');
		return $this->redirectToRoute('task_list');
	}
}
