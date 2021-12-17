<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\MallHelpMain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallHelpMain|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallHelpMain|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallHelpMain[]    findAll()
 * @method MallHelpMain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallHelpMainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallHelpMain::class);
    }

    // /**
    //  * @return MallHelpMain[] Returns an array of MallHelpMain objects
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
    public function findOneBySomeField($value): ?MallHelpMain
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
