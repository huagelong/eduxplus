<?php

namespace Eduxplus\EduxBundle\Repository;

use Eduxplus\EduxBundle\Entity\JwTeacher;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JwTeacher|null find($id, $lockMode = null, $lockVersion = null)
 * @method JwTeacher|null findOneBy(array $criteria, array $orderBy = null)
 * @method JwTeacher[]    findAll()
 * @method JwTeacher[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JwTeacherRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JwTeacher::class);
    }

    // /**
    //  * @return JwTeacher[] Returns an array of JwTeacher objects
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
    public function findOneBySomeField($value): ?JwTeacher
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
