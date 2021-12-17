<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\BaseRoleUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseRoleUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseRoleUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseRoleUser[]    findAll()
 * @method BaseRoleUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseRoleUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseRoleUser::class);
    }

    // /**
    //  * @return BaseRoleUser[] Returns an array of BaseRoleUser objects
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
    public function findOneBySomeField($value): ?BaseRoleUser
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
