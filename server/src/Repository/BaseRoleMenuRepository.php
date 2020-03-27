<?php

namespace App\Repository;

use App\Entity\BaseRoleMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BaseRoleMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseRoleMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseRoleMenu[]    findAll()
 * @method BaseRoleMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseRoleMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseRoleMenu::class);
    }

    // /**
    //  * @return BaseRoleMenu[] Returns an array of BaseRoleMenu objects
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
    public function findOneBySomeField($value): ?BaseRoleMenu
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
