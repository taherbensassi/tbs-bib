<?php

namespace App\Repository;

use App\Entity\TbsModule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TbsModule|null find($id, $lockMode = null, $lockVersion = null)
 * @method TbsModule|null findOneBy(array $criteria, array $orderBy = null)
 * @method TbsModule[]    findAll()
 * @method TbsModule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TbsModuleRepository extends ServiceEntityRepository
{
    /**
     * TbsModuleRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TbsModule::class);
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

}
