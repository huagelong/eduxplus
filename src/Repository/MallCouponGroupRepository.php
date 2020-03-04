<?php

namespace App\Repository;

use App\Entity\MallCouponGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MallCouponGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallCouponGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallCouponGroup[]    findAll()
 * @method MallCouponGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallCouponGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallCouponGroup::class);
    }

    // /**
    //  * @return MallCouponGroup[] Returns an array of MallCouponGroup objects
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
    public function findOneBySomeField($value): ?MallCouponGroup
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
