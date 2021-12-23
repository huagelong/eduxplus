<?php

namespace Eduxplus\QaBundle\Repository;

use Eduxplus\QaBundle\Entity\TeachQANode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachQANode|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachQANode|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachQANode[]    findAll()
 * @method TeachQANode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachQANodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachQANode::class);
    }

    // /**
    //  * @return TeachQANode[] Returns an array of TeachQANode objects
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
    public function findOneBySomeField($value): ?TeachQANode
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
