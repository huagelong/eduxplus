<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\MallNews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallNews|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallNews|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallNews[]    findAll()
 * @method MallNews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallNewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallNews::class);
    }

    // /**
    //  * @return MallNews[] Returns an array of MallNews objects
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
    public function findOneBySomeField($value): ?MallNews
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
