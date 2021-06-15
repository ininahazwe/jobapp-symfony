<?php

namespace App\Repository;

use App\Entity\ModeleOffreCommerciale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ModeleOffreCommerciale|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModeleOffreCommerciale|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModeleOffreCommerciale[]    findAll()
 * @method ModeleOffreCommerciale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModeleOffreCommercialeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModeleOffreCommerciale::class);
    }

    // /**
    //  * @return ModeleOffreCommerciale[] Returns an array of ModeleOffreCommerciale objects
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
    public function findOneBySomeField($value): ?ModeleOffreCommerciale
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
