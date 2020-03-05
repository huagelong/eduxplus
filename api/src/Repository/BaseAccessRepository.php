<?php

namespace App\Repository;

use App\Entity\BaseAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BaseAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseAccess[]    findAll()
 * @method BaseAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseAccessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseAccess::class);
    }

    // /**
    //  * @return BaseAccess[] Returns an array of BaseAccess objects
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
    public function findOneBySomeField($value): ?BaseAccess
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
