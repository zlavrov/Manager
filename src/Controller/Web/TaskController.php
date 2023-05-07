<?php

namespace App\Controller\Web;

use DateTime;
use App\Entity\Task;
use App\Entity\User;
use App\Form\CreateTaskFormType;
use App\Form\UpdateTaskFormType;
use App\Model\Out\Task\TaskListOut;
use App\Model\In\Task\TaskCreateIn;
use App\Model\In\Task\TaskUpdateIn;
use AutoMapperPlus\AutoMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Summary of TaskController
 */
class TaskController extends AbstractController
{
    /**
     * @var AutoMapperInterface $autoMapper
     */
    private $autoMapper;

    /**
     * @var ObjectRepository $taskRepository
     */
    private $taskRepository;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * TaskController constructor.
     * @param AutoMapperInterface $autoMapper
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(AutoMapperInterface $autoMapper, EntityManagerInterface $entityManager)
    {
        $this->autoMapper = $autoMapper;
        $this->entityManager = $entityManager;
        $this->taskRepository = $entityManager->getRepository(Task::class);
    }

    /**
     * @return array
     */
    #[Route('/task/create', name: 'TaskCreate')]
    public function CreateTack(Request $request, TaskCreateIn $taskCreateIn): Response
    {
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        $task = new TaskCreateIn();
        $form = $this->createForm(CreateTaskFormType::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->deadline = $task->deadline->format('Y-m-d H:i:s');
            $taskCreateIn = $this->autoMapper->mapToObject($task, new Task());
            $taskCreateIn->setUser($user);
            $this->entityManager->persist($taskCreateIn);
            $this->entityManager->flush();
            return $this->redirectToRoute('TaskList');
        }
        return $this->render('page/createTask.thml.twig', [
            'createTackForm' => $form->createView()
        ]);
    }

    /**
     * @param TaskUpdateIn $taskUpdateIn
     * @return array
     */
    #[Route('/task/update/{id}', name: 'TackUpdate')]
    public function TackUpdate(Request $request, TaskUpdateIn $taskUpdateIn, $id): Response
    {
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        $localTask = $this->taskRepository->find($id);
        $taskListOut =$this->autoMapper->map($localTask, TaskListOut::class);
        $taskListOut->deadline = new DateTime($taskListOut->deadline);
        $form = $this->createForm(UpdateTaskFormType::class, $taskListOut);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $answer = $this->autoMapper->mapToObject($form->getData(), $localTask);
            $this->entityManager->persist($answer);
            $this->entityManager->flush();
            return $this->redirectToRoute('TaskList');
        }
        return $this->render('page/updateTask.html.twig', [
            'updateTaskForm' => $form->createView()
        ]);
    }

    /**
     * Summary of getTaskList
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return Response|\Symfony\Component\HttpFoundation\RedirectResponse
     */
     #[Route('/task/list', name: 'TaskList', methods: ['GET'])]
    public function getTaskList(Request $request)
    {
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        $taskList = $this->taskRepository->filterData($request->query, $user);
        $answer = $this->autoMapper->mapMultiple($taskList, TaskListOut::class);
        return $this->render('page/tasklist.html.twig', [
            'tasklist' => $answer
        ]);
    }

    /**
     * Summary of deleteTaskById
     * @param mixed $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    #[Route('/task/delete/{id}', name: 'TaskDelete')]
    public function deleteTaskById($id): Response
    {
        $user = $this->getUser();
        if(!$user) {
            return $this->redirectToRoute('app_login');
        }
        $task = $this->taskRepository->find($id);
        $this->entityManager->remove($task);
        $this->entityManager->flush();
        return $this->redirectToRoute('TaskList');
    }
}
