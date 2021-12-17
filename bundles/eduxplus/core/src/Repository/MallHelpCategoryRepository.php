<?php

namespace Eduxplus\CoreBundle\Repository;

use Eduxplus\CoreBundle\Entity\MallHelpCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MallHelpCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method MallHelpCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method MallHelpCategory[]    findAll()
 * @method MallHelpCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MallHelpCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MallHelpCategory::class);
    }

    // /**
    //  * @return MallHelpCategory[] Returns an array of MallHelpCategory objects
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
    public function findOneBySomeField($value): ?MallHelpCategory
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
