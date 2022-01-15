<?php

namespace Eduxplus\CmsBundle\Repository;

use Eduxplus\CmsBundle\Entity\CmsHelpCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CmsHelpCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method CmsHelpCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method CmsHelpCategory[]    findAll()
 * @method CmsHelpCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CmsHelpCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CmsHelpCategory::class);
    }

    // /**
    //  * @return CmsHelpCategory[] Returns an array of CmsHelpCategory objects
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
    public function findOneBySomeField($value): ?CmsHelpCategory
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
