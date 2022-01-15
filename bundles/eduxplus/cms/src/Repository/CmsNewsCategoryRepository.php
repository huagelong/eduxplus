<?php

namespace Eduxplus\CmsBundle\Repository;

use Eduxplus\CmsBundle\Entity\CmsNewsCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsNewsCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsNewsCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsNewsCategory[]    findAll()
 * @method CmsNewsCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsNewsCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsNewsCategory::class);
    }

    // /**
    //  * @return CmsNewsCategory[] Returns an array of CmsNewsCategory objects
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
    public function findOneBySomeField($value): ?CmsNewsCategory
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
