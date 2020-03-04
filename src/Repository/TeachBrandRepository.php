<?php

namespace App\Repository;

use App\Entity\TeachBrand;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TeachBrand|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachBrand|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachBrand[]    findAll()
 * @method TeachBrand[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachBrandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachBrand::class);
    }

    // /**
    //  * @return TeachBrand[] Returns an array of TeachBrand objects
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
    public function findOneBySomeField($value): ?TeachBrand
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
