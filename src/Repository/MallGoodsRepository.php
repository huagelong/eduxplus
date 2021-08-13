<?php

namespace App\Repository;

use App\Entity\MallGoods;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallGoods|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallGoods|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallGoods[]    findAll()
 * @method MallGoods[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallGoodsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallGoods::class);
    }

    // /**
    //  * @return MallGoods[] Returns an array of MallGoods objects
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
    public function findOneBySomeField($value): ?MallGoods
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
