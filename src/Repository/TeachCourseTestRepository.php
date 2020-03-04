<?php

namespace App\Repository;

use App\Entity\TeachCourseTest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TeachCourseTest|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachCourseTest|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachCourseTest[]    findAll()
 * @method TeachCourseTest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachCourseTestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachCourseTest::class);
    }

    // /**
    //  * @return TeachCourseTest[] Returns an array of TeachCourseTest objects
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
    public function findOneBySomeField($value): ?TeachCourseTest
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
