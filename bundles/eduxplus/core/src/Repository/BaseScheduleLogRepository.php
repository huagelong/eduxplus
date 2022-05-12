<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\BaseScheduleLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseScheduleLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseScheduleLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseScheduleLog[]    findAll()
 * @method BaseScheduleLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseScheduleLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseScheduleLog::class);
    }

    // /**
    //  * @return BaseScheduleLog[] Returns an array of BaseScheduleLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BaseScheduleLog
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
