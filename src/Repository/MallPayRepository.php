<?php

namespace App\Repository;

use App\Entity\MallPay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MallPay|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallPay|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallPay[]    findAll()
 * @method MallPay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallPayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallPay::class);
    }

    // /**
    //  * @return MallPay[] Returns an array of MallPay objects
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
    public function findOneBySomeField($value): ?MallPay
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
