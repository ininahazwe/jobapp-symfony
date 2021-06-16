<?php

namespace App\Repository;

use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Entreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entreprise[]    findAll()
 * @method Entreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entreprise::class);
    }

    public function getNbMaxRecruteurs($entreprise = null)
    {
        $now = new \DateTime('now');
        $nombre = 0;
        $queryOffres = $this->getEntityManager()->getRepository('App\Entity\Offre')->createQueryBuilder('o');
        $queryOffres ->andWhere('o.entreprise = :entreprise')
            ->andWhere('o.debutContratAt <= :date')
            ->andWhere('o.finContratAt >= :date')
            ->setParameter('entreprise' , $entreprise)
            ->setParameter('date' , $now);
        $offres = $queryOffres->getQuery()->getResult();

        foreach ($offres as $offre)
        {
            if ($offre->getNombreRecruteurs() !== null){
                $nombre = $nombre + $offre->getNombreRecruteurs();
                if ($offre->getNombreRecruteurs() == 0){
                    return $nombre = 0;
                }
            }

        }
        return $nombre;
    }

    public function getAllEntreprisesAdmin($userId)
    {
        $user = $this->_em->getRepository("App:User")->find($userId);
        if($user->isSuperAdmin()){
            $query =  $this->createQueryBuilder('e')
                ->orderBy('e.id', 'ASC')
                ;
            return $query->getQuery()
                ->getResult();
        }elseif ($user->isSuperRecruteur()){
            $query = $this->createQueryBuilder('e');

            $ids = array();
            if (count($user->getRecruteursEntreprise())>0){
                foreach ($user->getRecruteursEntreprise() as $item){
                    $ids[] = $item->getId();
                }
                $query->andWhere('e.id IN (:ids)')
                    ->setParameter('ids', $ids);

                return $query->getQuery()->getResult() ;
            }else{
                return null;
            }
        }else{
            return null;
        }

    }
    // /**
    //  * @return Entreprise[] Returns an array of Entreprise objects
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
    public function findOneBySomeField($value): ?Entreprise
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