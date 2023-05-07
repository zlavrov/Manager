<?php

namespace App\Controller\Api;

use App\Entity\Task;
use App\Entity\User;
use App\Model\Out\User\UserListOut;
use App\Model\In\User\UserCreateIn;
use App\Model\In\User\UserUpdateIn;
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
 * Summary of ApiUserController
 */
class ApiUserController extends AbstractController {

    /**
     * @var AutoMapperInterface $autoMapper
     */
    private $autoMapper;

    /**
     * @var ObjectRepository $userRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * ApiUserController constructor.
     * @param AutoMapperInterface $autoMapper
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(AutoMapperInterface $autoMapper, EntityManagerInterface $entityManager)
    {
        $this->autoMapper = $autoMapper;
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository(User::class);
    }

    /**
     * @param UserCreateIn $userCreateIn
     * @return JsonResponse
     */
    #[Route('/api/user/create', name: 'ApiUserCreate', methods: ['POST'])]
    #[ParamConverter("userCreateIn", converter: "fos_rest.request_body")]
    public function UserCreate(UserCreateIn $userCreateIn): JsonResponse
    {
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(["status" => false, "message" => "user error auth"]);
        }
        $user = $this->autoMapper->mapToObject($userCreateIn, new User());
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new JsonResponse($user->getId());
    }

    /**
     * @param UserUpdateIn $userUpdateIn
     * @param int $id
     * @return JsonResponse
     */
    #[Route('/api/user/update/{id}', name: 'ApiUserUpdate', requirements: ['id' => '\d+'], methods: ['PATCH'])]
    #[ParamConverter("userUpdateIn", converter: "fos_rest.request_body")]
    public function userUpdate(UserUpdateIn $userUpdateIn, $id): JsonResponse
    {
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(["status" => false, "message" => "user error auth"]);
        }
        $localUser = $this->userRepository->find($id);
        $user = $this->autoMapper->mapToObject($userUpdateIn, $localUser);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return new JsonResponse($user->getId());
    }

    #[Route('/api/user/{id}', name: 'getApiUserById', requirements: ['id' => '\d+'], methods: ['GET'])]
    /**
     * Summary of getUserById
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUserById($id): JsonResponse
    {
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(["status" => false, "message" => "user error auth"]);
        }
        $localUser = $this->userRepository->find($id);
        return new JsonResponse($this->autoMapper->map($localUser, UserListOut::class));
    }

    #[Route('/api/user/list', name: 'getApiUserList', methods: ['GET'])]
    /**
     * Summary of getUserList
     * @return JsonResponse
     */
    public function getUserList()
    {   
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(["status" => false, "message" => "user error auth"]);
        }
        $userList = $this->userRepository->findAll();
        $answer = $this->autoMapper->mapMultiple($userList, UserListOut::class);
        return new JsonResponse($answer);
    }

    #[Route('/api/user/delete/{id}', name: 'deleteApiUserById', requirements: ['id' => '\d+'], methods: ['DELETE'])]
    /**
     * Summary of deleteUserById
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteUserById($id): JsonResponse
    {
        $user = $this->getUser();
        if(!$user) {
            return new JsonResponse(["status" => false, "message" => "user error auth"]);
        }
        $user = $this->userRepository->find($id);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return new JsonResponse($user->getId());
    }
}
