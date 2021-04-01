<?php

namespace App\Repository;

use App\Entity\SitePackage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SitePackage|null find($id, $lockMode = null, $lockVersion = null)
 * @method SitePackage|null findOneBy(array $criteria, array $orderBy = null)
 * @method SitePackage[]    findAll()
 * @method SitePackage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SitePackageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SitePackage::class);
    }

    // /**
    //  * @return SitePackage[] Returns an array of SitePackage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SitePackage
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
