<?php

namespace Eduxplus\EduxBundle\Repository;

use Eduxplus\EduxBundle\Entity\MallNewsMain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallNewsMain|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallNewsMain|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallNewsMain[]    findAll()
 * @method MallNewsMain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallNewsMainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallNewsMain::class);
    }

    // /**
    //  * @return MallNewsMain[] Returns an array of MallNewsMain objects
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
    public function findOneBySomeField($value): ?MallNewsMain
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
