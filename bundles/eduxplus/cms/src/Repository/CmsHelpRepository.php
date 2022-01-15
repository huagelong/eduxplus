<?php

namespace Eduxplus\CmsBundle\Repository;

use Eduxplus\CmsBundle\Entity\CmsHelp;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsHelp|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsHelp|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsHelp[]    findAll()
 * @method CmsHelp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsHelpRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsHelp::class);
    }

    // /**
    //  * @return CmsHelp[] Returns an array of CmsHelp objects
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
    public function findOneBySomeField($value): ?CmsHelp
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
