<?php

namespace App\Repository;

use App\Entity\JwSchool;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method JwSchool|null find($id, $lockMode = null, $lockVersion = null)
 * @method JwSchool|null findOneBy(array $criteria, array $orderBy = null)
 * @method JwSchool[]    findAll()
 * @method JwSchool[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JwSchoolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JwSchool::class);
    }

    // /**
    //  * @return JwSchool[] Returns an array of JwSchool objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JwSchool
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
