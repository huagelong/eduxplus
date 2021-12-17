<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\MallGoodsGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallGoodsGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallGoodsGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallGoodsGroup[]    findAll()
 * @method MallGoodsGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallGoodsGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallGoodsGroup::class);
    }

    // /**
    //  * @return MallGoodsGroup[] Returns an array of MallGoodsGroup objects
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
    public function findOneBySomeField($value): ?MallGoodsGroup
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
