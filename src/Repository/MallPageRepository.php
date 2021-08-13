<?php

namespace App\Repository;

use App\Entity\MallPage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallPage|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallPage|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallPage[]    findAll()
 * @method MallPage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallPageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallPage::class);
    }

    // /**
    //  * @return MallPage[] Returns an array of MallPage objects
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
    public function findOneBySomeField($value): ?MallPage
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
