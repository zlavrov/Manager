<?php

namespace App;

use DateTime;
use App\Entity\Task;
use App\Model\In\Task\TaskCreateIn;
use App\Model\In\Task\TaskUpdateIn;
use App\Model\Out\Task\TaskListOut;
use App\Model\In\Task\AbstractTaskIn;

use App\Entity\User;
use App\Model\In\User\UserCreateIn;
use App\Model\In\User\UserUpdateIn;
use App\Model\Out\User\UserListOut;
use App\Model\In\User\AbstractUserIn;

use AutoMapperPlus\AutoMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Annotation as Serializer;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;

/**
 * Summary of AutoMapperConfig
 */
class AutoMapperConfig implements AutoMapperConfiguratorInterface
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var AutoMapperInterface $autoMapper
     */
    private $autoMapper;
    
    /**
     * Summary of __construct
     * @param \AutoMapperPlus\AutoMapperInterface $autoMapper
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(AutoMapperInterface $autoMapper, EntityManagerInterface $entityManager)
    {
        $this->autoMapper = $autoMapper;
        $this->entityManager = $entityManager;
    }

    /**
     * Summary of configure
     * @param \AutoMapperPlus\Configuration\AutoMapperConfigInterface $config
     * @return void
     */
    public function configure(AutoMapperConfigInterface $config): void
    {
        $this->configureTask($config);
        $this->configureUser($config);
    }

    /**
     * Summary of configureTask
     * @param \AutoMapperPlus\Configuration\AutoMapperConfigInterface $config
     * @return void
     */
    public function configureTask(AutoMapperConfigInterface $config): void
    {            
        // TaskCreateIn model -> Task entity
        $config->registerMapping(TaskCreateIn::class, Task::class)
        ->forMember('status', function (TaskCreateIn $taskCreateIn){
            return Task::STATUS[$taskCreateIn->status];
        })
        ->forMember('deadline', function (TaskCreateIn $taskCreateIn) {
            return new DateTime($taskCreateIn->deadline);
        });

        // TaskUpdateIn model -> Task entity
        $config->registerMapping(TaskUpdateIn::class, Task::class)
        ->forMember('status', function (TaskUpdateIn $taskUpdateIn){
            return Task::STATUS[$taskUpdateIn->status];
        })
        ->forMember('deadline', function (TaskUpdateIn $taskUpdateIn) {
            return new DateTime($taskUpdateIn->deadline);
        });

        // Task entity -> TaskListOut model
        $config->registerMapping(Task::class, TaskListOut::class)
        ->forMember('status', function (Task $task){
            return array_flip($task::STATUS)[$task->getStatus()] ?? "undefined";
        })
        ->forMember('deadline', function (Task $task) {
            return $task->getDeadline()->format('Y-m-d H:i:s');
        });

        // TaskListOut model -> Task entity
        $config->registerMapping(TaskListOut::class, Task::class)
        ->forMember('status', function (TaskListOut $taskListOut){
            return Task::STATUS[$taskListOut->status];
        });
    }

    /**
     * Summary of configureUser
     * @param \AutoMapperPlus\Configuration\AutoMapperConfigInterface $config
     * @return void
     */
    public function configureUser(AutoMapperConfigInterface $config): void
    {            
        // UserCreateIn model -> User entity
        $config->registerMapping(UserCreateIn::class, User::class);

        // UserUpdateIn model -> User entity
        $config->registerMapping(UserUpdateIn::class, User::class);

        // User entity -> UserListOut model
        $config->registerMapping(User::class, UserListOut::class);

        // UserListOut model -> User entity
        $config->registerMapping(UserListOut::class, User::class);
    }
}
