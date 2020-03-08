<?php

namespace App\Repository;

use App\Entity\BaseOptionGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BaseOptionGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseOptionGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseOptionGroup[]    findAll()
 * @method BaseOptionGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseOptionGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseOptionGroup::class);
    }

    // /**
    //  * @return BaseOptionGroup[] Returns an array of BaseOptionGroup objects
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
    public function findOneBySomeField($value): ?BaseOptionGroup
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
