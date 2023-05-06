<?php

namespace App;

use DateTime;
use App\Entity\Task;
use App\Model\In\Task\TaskCreateIn;
use App\Model\In\Task\TaskUpdateIn;
use App\Model\Out\Task\TaskListOut;
use App\Model\In\Task\AbstractTaskIn;

use AutoMapperPlus\AutoMapperInterface;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\Annotation as Serializer;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;

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
    
    public function __construct(AutoMapperInterface $autoMapper, EntityManagerInterface $entityManager)
    {
        $this->autoMapper = $autoMapper;
        $this->entityManager = $entityManager;
    }

    public function configure(AutoMapperConfigInterface $config): void
    {
        $this->configureTask($config);
    }

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
}
