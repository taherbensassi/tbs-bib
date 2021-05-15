<?php

namespace App\Repository;

use App\Entity\Typo3Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Typo3Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Typo3Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Typo3Project[]    findAll()
 * @method Typo3Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Typo3ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Typo3Project::class);
    }

    // /**
    //  * @return Typo3Project[] Returns an array of Typo3Project objects
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
    public function findOneBySomeField($value): ?Typo3Project
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
