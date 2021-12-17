<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\MallStudyLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallStudyLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallStudyLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallStudyLog[]    findAll()
 * @method MallStudyLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallStudyLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallStudyLog::class);
    }

    // /**
    //  * @return MallStudyLog[] Returns an array of MallStudyLog objects
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
    public function findOneBySomeField($value): ?MallStudyLog
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
