<?php

namespace Eduxplus\QaBundle\Repository;

use Eduxplus\QaBundle\Entity\TeachQAChapterSub;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachQAChapterSub|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachQAChapterSub|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachQAChapterSub[]    findAll()
 * @method TeachQAChapterSub[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachQAChapterSubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachQAChapterSub::class);
    }

    // /**
    //  * @return TeachQAChapterSub[] Returns an array of TeachQAChapterSub objects
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
    public function findOneBySomeField($value): ?TeachQAChapterSub
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
