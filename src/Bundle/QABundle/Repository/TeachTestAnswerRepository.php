<?php

namespace App\Bundle\QABundle\Repository;

use App\Bundle\QABundle\Entity\TeachTestAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachTestAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachTestAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachTestAnswer[]    findAll()
 * @method TeachTestAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachTestAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachTestAnswer::class);
    }

    // /**
    //  * @return TeachTestAnswer[] Returns an array of TeachTestAnswer objects
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
    public function findOneBySomeField($value): ?TeachTestAnswer
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
