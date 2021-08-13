<?php

namespace App\Bundle\QABundle\Repository;

use App\Bundle\QABundle\Entity\TeachTestSub;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachTestSub|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachTestSub|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachTestSub[]    findAll()
 * @method TeachTestSub[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachTestSubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachTestSub::class);
    }

    // /**
    //  * @return TeachTestSub[] Returns an array of TeachTestSub objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeachTestSub
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
