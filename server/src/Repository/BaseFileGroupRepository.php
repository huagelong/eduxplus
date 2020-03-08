<?php

namespace App\Repository;

use App\Entity\BaseFileGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BaseFileGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseFileGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseFileGroup[]    findAll()
 * @method BaseFileGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseFileGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseFileGroup::class);
    }

    // /**
    //  * @return BaseFileGroup[] Returns an array of BaseFileGroup objects
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
    public function findOneBySomeField($value): ?BaseFileGroup
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
