<?php

namespace App\Repository;

use App\Entity\BaseRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BaseRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseRole[]    findAll()
 * @method BaseRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseRole::class);
    }

    // /**
    //  * @return BaseRole[] Returns an array of BaseRole objects
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
    public function findOneBySomeField($value): ?BaseRole
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
