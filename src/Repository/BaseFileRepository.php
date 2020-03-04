<?php

namespace App\Repository;

use App\Entity\BaseFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BaseFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseFile[]    findAll()
 * @method BaseFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseFile::class);
    }

    // /**
    //  * @return BaseFile[] Returns an array of BaseFile objects
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
    public function findOneBySomeField($value): ?BaseFile
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
