<?php

namespace App\Repository;

use App\Entity\BaseOpenAuth;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseOpenAuth|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseOpenAuth|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseOpenAuth[]    findAll()
 * @method BaseOpenAuth[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseOpenAuthRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseOpenAuth::class);
    }

    // /**
    //  * @return BaseOpenAuth[] Returns an array of BaseOpenAuth objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BaseOpenAuth
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
