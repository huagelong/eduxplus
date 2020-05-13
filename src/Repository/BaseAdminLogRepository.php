<?php

namespace App\Repository;

use App\Entity\BaseAdminLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseAdminLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseAdminLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseAdminLog[]    findAll()
 * @method BaseAdminLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseAdminLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseAdminLog::class);
    }

    // /**
    //  * @return BaseAdminLog[] Returns an array of BaseAdminLog objects
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
    public function findOneBySomeField($value): ?BaseAdminLog
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
