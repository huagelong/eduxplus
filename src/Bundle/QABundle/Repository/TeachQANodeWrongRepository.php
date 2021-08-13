<?php

namespace App\Bundle\QABundle\Repository;

use App\Bundle\QABundle\Entity\TeachQANodeWrong;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachQANodeWrong|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachQANodeWrong|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachQANodeWrong[]    findAll()
 * @method TeachQANodeWrong[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachQANodeWrongRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachQANodeWrong::class);
    }

    // /**
    //  * @return TeachQANodeWrong[] Returns an array of TeachQANodeWrong objects
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
    public function findOneBySomeField($value): ?TeachQANodeWrong
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
