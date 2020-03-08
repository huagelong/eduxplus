<?php

namespace App\Repository;

use App\Entity\JwClasses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method JwClasses|null find($id, $lockMode = null, $lockVersion = null)
 * @method JwClasses|null findOneBy(array $criteria, array $orderBy = null)
 * @method JwClasses[]    findAll()
 * @method JwClasses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JwClassesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JwClasses::class);
    }

    // /**
    //  * @return JwClasses[] Returns an array of JwClasses objects
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
    public function findOneBySomeField($value): ?JwClasses
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
