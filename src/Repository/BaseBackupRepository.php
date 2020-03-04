<?php

namespace App\Repository;

use App\Entity\BaseBackup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method BaseBackup|null find($id, $lockMode = null, $lockVersion = null)
 * @method BaseBackup|null findOneBy(array $criteria, array $orderBy = null)
 * @method BaseBackup[]    findAll()
 * @method BaseBackup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BaseBackupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BaseBackup::class);
    }

    // /**
    //  * @return BaseBackup[] Returns an array of BaseBackup objects
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
    public function findOneBySomeField($value): ?BaseBackup
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
