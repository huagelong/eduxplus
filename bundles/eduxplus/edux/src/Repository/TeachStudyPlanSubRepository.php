<?php

namespace Eduxplus\EduxBundle\Repository;

use Eduxplus\EduxBundle\Entity\TeachStudyPlanSub;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachStudyPlanSub|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachStudyPlanSub|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachStudyPlanSub[]    findAll()
 * @method TeachStudyPlanSub[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachStudyPlanSubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachStudyPlanSub::class);
    }

    // /**
    //  * @return TeachStudyPlanSub[] Returns an array of TeachStudyPlanSub objects
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
    public function findOneBySomeField($value): ?TeachStudyPlanSub
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
