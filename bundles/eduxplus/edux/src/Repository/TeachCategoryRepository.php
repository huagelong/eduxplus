<?php

namespace Eduxplus\EduxBundle\Repository;

use Eduxplus\EduxBundle\Entity\TeachCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachCategory[]    findAll()
 * @method TeachCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachCategory::class);
    }

    // /**
    //  * @return TeachCategory[] Returns an array of TeachCategory objects
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
    public function findOneBySomeField($value): ?TeachCategory
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
