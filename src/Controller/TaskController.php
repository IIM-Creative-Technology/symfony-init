<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class TaskController extends AbstractController
{
    /** @var TaskRepository */
    private $taskRepository;

    public function __construct(EntityManagerInterface $objectManager)
    {
        $this->taskRepository = $objectManager->getRepository(Task::class);
    }

    /**
     * @Route("/tasks", name="task_list")
     */
    public function taskList(): Response
    {
        $tasks = $this->taskRepository->findAll();

        return $this->render('task/index.html.twig', [
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("tasks/create", name="task_create")
     * @param Request $request
     * @return Response
     */
    public function createTask(Request $request): Response
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $this->taskRepository->save($task);

            return $this->redirectToRoute('task_list');
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("tasks/{id}/update", name="update_task")
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function updateTask(int $id, Request $request): Response
    {
        $task = $this->taskRepository->find($id);

        if (null === $task) {
            throw new NotFoundHttpException();
        }

        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task = $form->getData();
            $this->taskRepository->save($task);

            return $this->redirectToRoute('task_list');
        }

        return $this->renderForm('task/new.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * @Route("/tasks/{id}", name="task_details")
     * @param int $id
     * @return Response
     */
    public function taskDetail(int $id): Response
    {
        $task = $this->taskRepository->find($id);

        if (null === $task) {
            throw new NotFoundHttpException();
        }

        return $this->render('task/details.html.twig', [
            'task' => $task
        ]);
    }

    /**
     * @Route("/tasks/{id}/delete", name="delete_task")
     * @param int $id
     * @return Response
     */
    public function deleteTask(int $id): Response
    {
        $task = $this->taskRepository->find($id);

        if (null === $task) {
            throw new NotFoundHttpException();
        }

        $this->taskRepository->delete($task);

        return $this->redirectToRoute('task_list');
    }


}
