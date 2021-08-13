<?php

namespace App\Repository;

use App\Entity\TeachCourseChapter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachCourseChapter|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachCourseChapter|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachCourseChapter[]    findAll()
 * @method TeachCourseChapter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachCourseChapterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachCourseChapter::class);
    }

    // /**
    //  * @return TeachCourseChapter[] Returns an array of TeachCourseChapter objects
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
    public function findOneBySomeField($value): ?TeachCourseChapter
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
