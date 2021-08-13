<?php

namespace App\Repository;

use App\Entity\BaseLoginLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseLoginLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseLoginLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseLoginLog[]    findAll()
 * @method BaseLoginLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseLoginLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseLoginLog::class);
    }

    // /**
    //  * @return BaseLoginLog[] Returns an array of BaseLoginLog objects
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
    public function findOneBySomeField($value): ?BaseLoginLog
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
