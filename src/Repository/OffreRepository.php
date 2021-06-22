<?php

namespace App\Repository;

use App\Entity\Offre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offre[]    findAll()
 * @method Offre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offre::class);
    }
    public function getOffresActiveNotFactured()
    {
        $now = new \DateTime('now');
        $query = $this->createQueryBuilder('o')
            ->andWhere('o.debutContratAt <= :date')
            ->andWhere('o.finContratAt >= :date')
            ->andWhere('o.isFacture = 0')
            ->andWhere('o.prix > 0')
            ->setParameter('date' , $now);

        return $query->getQuery()
            ->getResult()
            ;

    }
    public function getOffreActiveNotFactured($entreprise, $prix = false)
    {
    $now = new \DateTime('now');
    $query = $this->createQueryBuilder('o')
        ->andWhere('o.debutContratAt <= :date')
        ->andWhere('o.finContratAt >= :date')
        ->andWhere('o.isFacture = 0')
        ->andWhere('o.entreprise = :entreprise')
        ->andWhere('o.prix > 0')
        ->setParameter('entreprise' , $entreprise)
        ->setParameter('date' , $now);

    if ($prix){
        $price = 0 ;
        $result = $query->getQuery()->getResult();
        foreach ($result as $item){
            $price += $item->getPrix();
        }

        return $price;
    }
    return $query->getQuery()
        ->getResult()
        ;

}

    public function genererRef()
    {

        $query = $this->getEntityManager()->getRepository('App\Entity\Facture')->createQueryBuilder('f');
        $query->addOrderBy('f.id' ,'DESC')
            ->setMaxResults(1);

        $facture = current($query->getQuery()->getResult());


        $referance = "10000001";
        if ($facture){
            $int_value = (int) $facture->getReference();
            $referance = $int_value + 1;
        }

        return $referance ;
    }
    // /**
    //  * @return Offre[] Returns an array of Offre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offre
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
