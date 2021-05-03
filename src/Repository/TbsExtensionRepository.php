<?php

namespace App\Repository;

use App\Entity\TbsExtension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbsExtension|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbsExtension|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbsExtension[]    findAll()
 * @method TbsExtension[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbsExtensionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TbsExtension::class);
    }

    // /**
    //  * @return TbsExtension[] Returns an array of TbsExtension objects
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
    public function findOneBySomeField($value): ?TbsExtension
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
