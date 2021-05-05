<?php

namespace App\Repository;

use App\Entity\Typo3Version;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Typo3Version|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typo3Version|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typo3Version[]    findAll()
 * @method Typo3Version[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Typo3VersionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typo3Version::class);
    }

    // /**
    //  * @return Typo3Version[] Returns an array of Typo3Version objects
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
    public function findOneBySomeField($value): ?Typo3Version
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
