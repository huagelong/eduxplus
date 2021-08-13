<?php

namespace App\Bundle\QABundle\Repository;

use App\Bundle\QABundle\Entity\TeachTestOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachTestOrder|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachTestOrder|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachTestOrder[]    findAll()
 * @method TeachTestOrder[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachTestOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachTestOrder::class);
    }

    // /**
    //  * @return TeachTestOrder[] Returns an array of TeachTestOrder objects
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
    public function findOneBySomeField($value): ?TeachTestOrder
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
