<?php

namespace App\Controller\Api;

use DateTime;
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

/**
 * Summary of ApiTaskController
 */
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
     * @return JsonResponse
     */
    #[Route('/api/task/create', name: 'ApiTaskCreate', methods: ['POST'])]
    #[ParamConverter("taskCreateIn", converter: "fos_rest.request_body")]
    public function TackCreate(TaskCreateIn $taskCreateIn): JsonResponse
    {
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(["status" => false, "message" => "user error auth"]);
        }
        $diff = date_diff((new DateTime('now')), (new DateTime($taskCreateIn->deadline)));
        if ($diff->invert == 1) {
            return new JsonResponse(["status" => false, "message" => "validation error time"]);
        }
        $task = $this->autoMapper->mapToObject($taskCreateIn, new Task());
        $testuser = $this->entityManager->getRepository(User::class)->find(1);
        $task->setUser($testuser);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return new JsonResponse($task);
    }

    /**
     * @param TaskUpdateIn $taskUpdateIn
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/api/tack/update/{id}', name: 'ApiTackUpdate', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[ParamConverter("taskUpdateIn", converter: "fos_rest.request_body")]
    public function TackUpdate(TaskUpdateIn $taskUpdateIn, $id): JsonResponse
    {
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(["status" => false, "message" => "user error auth"]);
        }
        $diff = date_diff((new DateTime('now')), (new DateTime($taskUpdateIn->deadline)));
        if ($diff->invert == 1) {
            return new JsonResponse(["status" => false, "message" => "validation error time"]);
        }
        $localTask = $this->taskRepository->find($id);
        $task = $this->autoMapper->mapToObject($taskUpdateIn, $localTask);
        $this->entityManager->persist($task);
        $this->entityManager->flush();
        return new JsonResponse($task->getId());
    }

    /**
     * Summary of getTackById
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Route('/api/tack/{id}', name: 'getApiTackById', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function getTackById($id): JsonResponse
    {
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(["status" => false, "message" => "user error auth"]);
        }
        $localTask = $this->taskRepository->find($id);
        return new JsonResponse($this->autoMapper->map($localTask, TaskListOut::class));
    }

    /**
     * Summary of getTaskList
     * @return JsonResponse
     */
    #[Route('/api/task/list', name: 'getApiTaskList', methods: ['GET'])]
    public function getTaskList()
    {
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(["status" => false, "message" => "user error auth"]);
        }
        $taskList = $this->taskRepository->findAll();
        $answer = $this->autoMapper->mapMultiple($taskList, TaskListOut::class);
        return new JsonResponse($answer);
    }

    /**
     * Summary of deleteTaskById
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    #[Route('/api/task/delete/{id}', name: 'deleteApiTaskById', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    public function deleteTaskById($id): JsonResponse
    {
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(["status" => false, "message" => "user error auth"]);
        }
        $task = $this->taskRepository->find($id);
        $this->entityManager->remove($task);
        $this->entityManager->flush();
        return new JsonResponse($task->getId());
    }
}
