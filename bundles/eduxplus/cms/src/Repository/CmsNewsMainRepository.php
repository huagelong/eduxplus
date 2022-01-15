<?php

namespace Eduxplus\CmsBundle\Repository;

use Eduxplus\CmsBundle\Entity\CmsNewsMain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsNewsMain|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsNewsMain|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsNewsMain[]    findAll()
 * @method CmsNewsMain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsNewsMainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsNewsMain::class);
    }

    // /**
    //  * @return CmsNewsMain[] Returns an array of CmsNewsMain objects
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
    public function findOneBySomeField($value): ?CmsNewsMain
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
