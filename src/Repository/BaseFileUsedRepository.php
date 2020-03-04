<?php

namespace App\Repository;

use App\Entity\BaseFileUsed;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BaseFileUsed|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseFileUsed|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseFileUsed[]    findAll()
 * @method BaseFileUsed[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseFileUsedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseFileUsed::class);
    }

    // /**
    //  * @return BaseFileUsed[] Returns an array of BaseFileUsed objects
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
    public function findOneBySomeField($value): ?BaseFileUsed
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
