<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\BaseDictData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BaseDictData|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseDictData|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseDictData[]    findAll()
 * @method BaseDictData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseDictDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseDictData::class);
    }

    // /**
    //  * @return BaseDictData[] Returns an array of BaseDictData objects
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
    public function findOneBySomeField($value): ?BaseDictData
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
