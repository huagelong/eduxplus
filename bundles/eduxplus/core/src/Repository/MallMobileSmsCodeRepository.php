<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\MallMobileSmsCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallMobileSmsCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallMobileSmsCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallMobileSmsCode[]    findAll()
 * @method MallMobileSmsCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallMobileSmsCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallMobileSmsCode::class);
    }

    // /**
    //  * @return MallMobileSmsCode[] Returns an array of MallMobileSmsCode objects
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
    public function findOneBySomeField($value): ?MallMobileSmsCode
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
