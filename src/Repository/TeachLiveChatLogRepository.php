<?php

namespace App\Repository;

use App\Entity\TeachLiveChatLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachLiveChatLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachLiveChatLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachLiveChatLog[]    findAll()
 * @method TeachLiveChatLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachLiveChatLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachLiveChatLog::class);
    }

    // /**
    //  * @return TeachLiveChatLog[] Returns an array of TeachLiveChatLog objects
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
    public function findOneBySomeField($value): ?TeachLiveChatLog
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
