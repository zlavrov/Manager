<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Entity\User;
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

class ApiTaskController extends AbstractController {

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
     * ApiTaskController constructor.
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
     * @param TaskCreateIn $taskCreateIn
     * @return array
     */
    #[Route('/api/task/create', name: 'ApiTaskCreate', methods: ['POST'])]
    #[ParamConverter("taskCreateIn", converter: "fos_rest.request_body")]
    public function TackCreate(TaskCreateIn $taskCreateIn): JsonResponse
    {
        $task = $this->autoMapper->mapToObject($taskCreateIn, new Task());
        $testuser = $this->entityManager->getRepository(User::class)->find(1);
        $task->setUser($testuser);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return new JsonResponse($task);
    }

    /**
     * @param TaskUpdateIn $taskUpdateIn
     * @return array
     */
    #[Route('/api/tack/update/{id}', name: 'ApiTackUpdate', methods: ['PATCH'])]
    #[ParamConverter("taskUpdateIn", converter: "fos_rest.request_body")]
    public function TackUpdate(TaskUpdateIn $taskUpdateIn, $id): JsonResponse
    {   dd($taskUpdateIn);
        $localTask = $this->taskRepository->find($id);
        dd($localTask);
        $task = $this->autoMapper->mapToObject($taskUpdateIn, $localTask);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return new JsonResponse($task->getId());
    }

    #[Route('/api/tack/{id}', name: 'getApiTackById', methods: ['GET'])]
    public function getTackById($id): JsonResponse
    {
        $localTask = $this->taskRepository->find($id);
        return new JsonResponse($this->autoMapper->map($localTask, TaskListOut::class));
    }

    #[Route('/api/task/list', name: 'getApiTaskList', methods: ['GET'])]
    public function getTaskList()
    {
        $taskList = $this->taskRepository->findAll();
        $answer = $this->autoMapper->mapMultiple($taskList, TaskListOut::class);
        return new JsonResponse($answer);
    }

    #[Route('/api/task/delete/{id}', name: 'deleteApiTaskById', methods: ['DELETE'])]
    public function deleteTaskById($id): JsonResponse
    {
        $task = $this->taskRepository->find($id);
        $this->entityManager->remove($task);
        $this->entityManager->flush();
        return new JsonResponse($task->getId());
    }
}
