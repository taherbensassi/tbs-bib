<?php

namespace App\Repository;

use App\Entity\ContentElements;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ContentElements|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContentElements|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContentElements[]    findAll()
 * @method ContentElements[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContentElementsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContentElements::class);
    }

    // /**
    //  * @return ContentElements[] Returns an array of ContentElements objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ContentElements
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
