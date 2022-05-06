<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\BaseDictType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseDictType|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseDictType|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseDictType[]    findAll()
 * @method BaseDictType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseDictTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseDictType::class);
    }

    // /**
    //  * @return BaseDictType[] Returns an array of BaseDictType objects
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
    public function findOneBySomeField($value): ?BaseDictType
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
