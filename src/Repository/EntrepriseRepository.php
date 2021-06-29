<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Entreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method Entreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entreprise[]    findAll()
 * @method Entreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntrepriseRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginator;

    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Entreprise::class);
        $this->paginator = $paginator;
    }

    public function findAllEntreprise($user)
    {
        $query = $this->createQueryBuilder('e');
        if($user->isSuperAdmin()){
            $query//->andWhere('a.isActive = true')
            ->addOrderBy('e.createdAt', 'DESC')
                ->getQuery();

            return $query;
        } elseif ($user->isSuperRecruteur() || $user->isRecruteur()){
            $ids = array();
            foreach($user->getRecruteursEntreprise() as $entreprise){
                if (!in_array($entreprise->getId(), $ids)){
                    $ids[$entreprise->getId()] = $entreprise->getId();
                }
            }
            foreach($user->getEntreprises() as $entreprise){
                if (!in_array($entreprise->getId(), $ids)){
                    $ids[$entreprise->getId()] = $entreprise->getId();
                }
            }

            $query->andWhere('e.id IN (:entreprises)')
                ->addOrderBy('e.createdAt', 'DESC')
                ->setParameter('entreprises', $ids)
                ->getQuery()
            ;

            return $query;
        }

    }

    /**
     * @param null $entreprise
     * @return mixed
     */
    public function getNbMaxRecruteurs($entreprise = null): mixed
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
            if ($offre->getNombreRecruteurs() != null){
                $nombre = $nombre + $offre->getNombreRecruteurs();
                if ($offre->getNombreRecruteurs() == 0){
                    return $nombre = 0;
                }
            }else{
                $nombre = 0;
            }
        }
        return $nombre;
    }

    /**
     * @param SearchData $search
     * @param $userId
     * @return PaginationInterface
     */
    public function getAllEntreprisesAdmin(SearchData $search, $userId): PaginationInterface
    {
        $user = $this->_em->getRepository("App:User")->find($userId);
        if($user->isSuperAdmin()){
            $query =  $this->createQueryBuilder('e')
                ->orderBy('e.id', 'ASC')
                ;

            if(!empty($search->q)){
                $query
                    ->andWhere('e.name LIKE :q')
                    ->setParameter('q', "%{$search->q}%");
            }

            return $this->paginator->paginate(
                $query->getQuery(),
                $search->page,
                2
            );
        }elseif ($user->isSuperRecruteur()) {
            $query = $this->createQueryBuilder('e');

            $ids = array();
            if (count($user->getRecruteursEntreprise()) > 0) {
                foreach ($user->getRecruteursEntreprise() as $item) {
                    $ids[] = $item->getId();
                }
                $query->andWhere('e.id IN (:ids)')
                    ->setParameter('ids', $ids);

                if (!empty($search->q)) {
                    $query = $query
                        ->andWhere('e.name LIKE :q')
                        ->setParameter('q', "%{$search->q}%");
                }

                return $this->paginator->paginate(
                    $query->getQuery(),
                    $search->page,
                    2
                );


            } else {
                //return null;
            }
        }else{
            //return null;
        }

    }

    /**
     * @return int|string
     */
    public function genererRef(): int|string
    {
        $query = $this->getEntityManager()->getRepository('App\Entity\Entreprise')->createQueryBuilder('e');
        $query->addOrderBy('e.id' ,'DESC')
            ->setMaxResults(1);

        $entreprise = current($query->getQuery()->getResult());


        $referance = "10000001";
        if ($entreprise){
            $int_value = (int) $entreprise->getRefClient();
            $referance = $int_value + 1;
        }

        return $referance ;
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getEntreprisesUser($user): mixed
    {
        $ids = array();

        if ($user->isSuperAdmin() ){
            $entreprises = $this->findAll();
            $ids = array();
            foreach($entreprises as $entreprise) {
                if ($entreprise->canCreateAnnonce()) {
                    $ids[$entreprise->getId()] = $entreprise->getId();
                }
            }
            $query = $this->createQueryBuilder('d')
                ->select('d')
                ->andWhere('d.id IN (:ids)')
                ->orderBy('d.name' ,  'ASC')
                ->setParameter('ids',$ids);
            return $query;

        }elseif($user->isSuperRecruteur()){
            $ids = array();
            foreach($user->getRecruteursEntreprise() as $entreprise) {
                if ($entreprise->canCreateAnnonce()){
                    if (!in_array($entreprise->getId(), $ids)) {
                        $ids[$entreprise->getId()] = $entreprise->getId();
                    }
                }
            }
            foreach($user->getEntreprises() as $entreprise) {
                if ($entreprise->canCreateAnnonce()){
                    if (!in_array($entreprise->getId(), $ids)) {
                        $ids[$entreprise->getId()] = $entreprise->getId();
                    }
                 }
            }
        }elseif($user->isRecruteur())
        {
            foreach($user->getEntreprises() as $entreprise){
                if ($entreprise->canCreateAnnonce()){
                    if (!in_array($entreprise->getId(), $ids)){
                        $ids[$entreprise->getId()] = $entreprise->getId();
                    }
                }
            }

        }
        $query = $this->createQueryBuilder('d')
            ->andWhere('d.id IN (:ids)')
            ->orderBy('d.name' ,  'ASC')
            ->setParameter('ids',$ids)
            ;
         return $query;
    }
}
