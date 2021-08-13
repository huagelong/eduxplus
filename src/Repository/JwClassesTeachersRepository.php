<?php

namespace App\Repository;

use App\Entity\JwClassesTeachers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JwClassesTeachers|null find($id, $lockMode = null, $lockVersion = null)
 * @method JwClassesTeachers|null findOneBy(array $criteria, array $orderBy = null)
 * @method JwClassesTeachers[]    findAll()
 * @method JwClassesTeachers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JwClassesTeachersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JwClassesTeachers::class);
    }

    // /**
    //  * @return JwClassesTeachers[] Returns an array of JwClassesTeachers objects
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
    public function findOneBySomeField($value): ?JwClassesTeachers
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
