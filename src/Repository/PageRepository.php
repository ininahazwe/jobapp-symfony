<?php

namespace App\Repository;

use App\Entity\Page;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Page|null find($id, $lockMode = null, $lockVersion = null)
 * @method Page|null findOneBy(array $criteria, array $orderBy = null)
 * @method Page[]    findAll()
 * @method Page[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PageRepository extends ServiceEntityRepository
{


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Page::class);
    }

    public function search($mots = null){
        $query = $this->createQueryBuilder('p');

        if($mots ==! null){
            $query->andWhere('MATCH_AGAINST(p.title, p.content) AGAINST(:mots boolean)>0')
                ->setParameter('mots', $mots);
        }

        /*if ($mots){
            $query->andWhere('p.title LIKE :mots')
                ->setParameter('mots' ,  '%' . $mots . '%');
        }*/
        return $query->getQuery()->getResult();
    }
}
