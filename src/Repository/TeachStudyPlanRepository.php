<?php

namespace App\Repository;

use App\Entity\TeachStudyPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachStudyPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachStudyPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachStudyPlan[]    findAll()
 * @method TeachStudyPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachStudyPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachStudyPlan::class);
    }

    // /**
    //  * @return TeachStudyPlan[] Returns an array of TeachStudyPlan objects
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
    public function findOneBySomeField($value): ?TeachStudyPlan
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
