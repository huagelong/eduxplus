<?php

namespace Eduxplus\CmsBundle\Repository;

use Eduxplus\CmsBundle\Entity\CmsPageMain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsPageMain|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsPageMain|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsPageMain[]    findAll()
 * @method CmsPageMain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsPageMainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsPageMain::class);
    }

    // /**
    //  * @return CmsPageMain[] Returns an array of CmsPageMain objects
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
    public function findOneBySomeField($value): ?CmsPageMain
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
