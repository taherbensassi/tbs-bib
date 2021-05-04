<?php

namespace App\Repository;

use App\Entity\TbsExtension;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
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

    /**
     * @param string $filter
     * @param string $field
     * @return int|mixed|string
     */
    public function filterByOrder(string $filter, string $field)
    {
        return $this->createQueryBuilder('t')
            ->orderBy('t.'.$field, $filter)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $version
     * @return ArrayCollection
     */
    public function filerByVersion(string $version): ArrayCollection
    {
        $versionArray = array();
        $versionArray[] = $version;


        $ar = new ArrayCollection();
        $qb = $this->createQueryBuilder('m');
        $qb ->select('m');
        foreach($versionArray as $item){
            $qb->andWhere('m.typo3Version LIKE :v')
                ->setParameter('v', '%"'.$item.'"%');
        }
        $results = $qb->getQuery()->getResult();
        foreach ($results as $result){
            $ar[] = $result;
        }

        return $ar;
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
