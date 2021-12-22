<?php

namespace Eduxplus\QABundle\Repository;

use Eduxplus\QABundle\Entity\TeachQANodeSub;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachQANodeSub|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachQANodeSub|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachQANodeSub[]    findAll()
 * @method TeachQANodeSub[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachQANodeSubRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachQANodeSub::class);
    }

    // /**
    //  * @return TeachQANodeSub[] Returns an array of TeachQANodeSub objects
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
    public function findOneBySomeField($value): ?TeachQANodeSub
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
