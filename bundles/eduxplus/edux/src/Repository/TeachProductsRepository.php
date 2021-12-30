<?php

namespace Eduxplus\EduxBundle\Repository;

use Eduxplus\EduxBundle\Entity\TeachProducts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachProducts|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachProducts|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachProducts[]    findAll()
 * @method TeachProducts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachProducts::class);
    }

    // /**
    //  * @return TeachProducts[] Returns an array of TeachProducts objects
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
    public function findOneBySomeField($value): ?TeachProducts
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
