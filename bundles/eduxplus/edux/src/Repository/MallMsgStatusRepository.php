<?php

namespace Eduxplus\EduxBundle\Repository;

use Eduxplus\EduxBundle\Entity\MallMsgStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallMsgStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallMsgStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallMsgStatus[]    findAll()
 * @method MallMsgStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallMsgStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallMsgStatus::class);
    }

    // /**
    //  * @return MallMsgStatus[] Returns an array of MallMsgStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MallMsgStatus
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
