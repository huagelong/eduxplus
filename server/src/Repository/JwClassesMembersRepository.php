<?php

namespace App\Repository;

use App\Entity\JwClassesMembers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method JwClassesMembers|null find($id, $lockMode = null, $lockVersion = null)
 * @method JwClassesMembers|null findOneBy(array $criteria, array $orderBy = null)
 * @method JwClassesMembers[]    findAll()
 * @method JwClassesMembers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JwClassesMembersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JwClassesMembers::class);
    }

    // /**
    //  * @return JwClassesMembers[] Returns an array of JwClassesMembers objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JwClassesMembers
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
