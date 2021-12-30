<?php

namespace Eduxplus\EduxBundle\Repository;

use Eduxplus\EduxBundle\Entity\MallNewsCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallNewsCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallNewsCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallNewsCategory[]    findAll()
 * @method MallNewsCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallNewsCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallNewsCategory::class);
    }

    // /**
    //  * @return MallNewsCategory[] Returns an array of MallNewsCategory objects
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
    public function findOneBySomeField($value): ?MallNewsCategory
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
