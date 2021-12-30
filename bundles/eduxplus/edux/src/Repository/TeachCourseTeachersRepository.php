<?php

namespace Eduxplus\EduxBundle\Repository;

use Eduxplus\EduxBundle\Entity\TeachCourseTeachers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachCourseTeachers|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachCourseTeachers|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachCourseTeachers[]    findAll()
 * @method TeachCourseTeachers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachCourseTeachersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachCourseTeachers::class);
    }

    // /**
    //  * @return TeachCourseTeachers[] Returns an array of TeachCourseTeachers objects
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
    public function findOneBySomeField($value): ?TeachCourseTeachers
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
