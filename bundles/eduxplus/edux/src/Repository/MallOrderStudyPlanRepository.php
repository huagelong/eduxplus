<?php

namespace Eduxplus\EduxBundle\Repository;

use Eduxplus\EduxBundle\Entity\MallOrderStudyPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallOrderStudyPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallOrderStudyPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallOrderStudyPlan[]    findAll()
 * @method MallOrderStudyPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallOrderStudyPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallOrderStudyPlan::class);
    }

    // /**
    //  * @return MallOrderStudyPlan[] Returns an array of MallOrderStudyPlan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MallOrderStudyPlan
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
