<?php

namespace App\Repository;

use App\Entity\MallGoodsPayType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MallGoodsPayType|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallGoodsPayType|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallGoodsPayType[]    findAll()
 * @method MallGoodsPayType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallGoodsPayTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallGoodsPayType::class);
    }

    // /**
    //  * @return MallGoodsPayType[] Returns an array of MallGoodsPayType objects
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
    public function findOneBySomeField($value): ?MallGoodsPayType
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
