<?php

namespace App\Repository;

use App\Entity\TeachCourseMaterials;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TeachCourseMaterials|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeachCourseMaterials|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeachCourseMaterials[]    findAll()
 * @method TeachCourseMaterials[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeachCourseMaterialsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeachCourseMaterials::class);
    }

    // /**
    //  * @return TeachCourseMaterials[] Returns an array of TeachCourseMaterials objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeachCourseMaterials
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
