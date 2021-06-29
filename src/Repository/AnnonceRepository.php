<?php

namespace App\Repository;

use App\Entity\Annonce;
use App\Entity\User;
use App\Form\AnnonceType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Annonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Annonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Annonce[]    findAll()
 * @method Annonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Annonce::class);
    }

    public function findAllActiveQuery($user)
    {
        $query = $this->createQueryBuilder('a');
        if($user->isSuperAdmin()){
            $query//->andWhere('a.isActive = true')
                ->addOrderBy('a.createdAt', 'DESC')
                ->getQuery();

            return $query;
        } elseif ($user->isSuperRecruteur() || $user->isRecruteur()){
            $ids = $user->getEntrepriseIds();
            $query->andWhere('a.entreprise IN (:entreprises)')
                ->addOrderBy('a.createdAt', 'DESC')
                ->setParameter('entreprises', $ids)
                ->getQuery()
            ;

            return $query;
        }
    }

    /**
     * @return array
     */
    public function findLatest(): array
    {
        return $this->findActiveQuery()
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return QueryBuilder
     */
    public function findActiveQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->where('a.isActive = true');
    }
}
