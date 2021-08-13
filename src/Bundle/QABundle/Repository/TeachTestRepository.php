<?php

namespace App\Bundle\QABundle\Repository;

use App\Bundle\QABundle\Entity\TeachTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachTest[]    findAll()
 * @method TeachTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachTest::class);
    }

    // /**
    //  * @return TeachTest[] Returns an array of TeachTest objects
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
    public function findOneBySomeField($value): ?TeachTest
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
