<?php

namespace App\Repository;

use App\Entity\TeachCourse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachCourse|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachCourse|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachCourse[]    findAll()
 * @method TeachCourse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachCourseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachCourse::class);
    }

    // /**
    //  * @return TeachCourse[] Returns an array of TeachCourse objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeachCourse
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
