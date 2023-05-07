<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    /**
     * Summary of __construct
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    /**
     * Summary of save
     * @param \App\Entity\Task $entity
     * @param mixed $flush
     * @return void
     */
    public function save(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Summary of remove
     * @param \App\Entity\Task $entity
     * @param mixed $flush
     * @return void
     */
    public function remove(Task $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Summary of filterData
     * @param mixed $data
     * @param mixed $user
     * @return mixed
     */
    public function filterData($data, $user) {

        $query = $this->createQueryBuilder('t');

        if(!in_array('ROLE_ADMIN', $user->getRoles())) {
            $query->andWhere('t.user = :val')
            ->setParameter('val', $user->getId());
        }

        if(!empty($data->get('title'))) {
            $query->andWhere('t.title LIKE :title')
                ->setParameter('title', '%' . $data->get('title') . '%');
        }
    
        if(!empty($data->get('status'))) {
            $query->andWhere('t.status = :status')
                ->setParameter('status', Task::STATUS[$data->get('status')]);
        }
    
        if(!empty($data->get('start')) && !empty($data->get('stop'))) {
    
            $query->andWhere('t.deadline BETWEEN :start AND :stop')
                ->setParameter('start', $data->get('start'))
                ->setParameter('stop', $data->get('stop'));
    
        } else if (!empty($data->get('start'))) {
    
            $query->andWhere('t.deadline > :start')
                ->setParameter('start', $data->get('start'));
        }

        if(!empty($data->get('sort'))) {
            $query->orderBy('t.' . $data->get('sort'), 'ASC');
        }
    
        return $query->getQuery()->getResult();

    }
//    /**
//     * @return Task[] Returns an array of Task objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Task
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
