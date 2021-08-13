<?php

namespace App\Repository;

use App\Entity\BaseMenu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseMenu|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseMenu|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseMenu[]    findAll()
 * @method BaseMenu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseMenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseMenu::class);
    }

    // /**
    //  * @return BaseMenu[] Returns an array of BaseMenu objects
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
    public function findOneBySomeField($value): ?BaseMenu
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
