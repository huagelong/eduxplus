<?php

namespace App\Repository;

use App\Entity\BaseOption;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseOption|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseOption|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseOption[]    findAll()
 * @method BaseOption[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseOptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseOption::class);
    }

    // /**
    //  * @return BaseOption[] Returns an array of BaseOption objects
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
    public function findOneBySomeField($value): ?BaseOption
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
