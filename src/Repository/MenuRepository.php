<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Menu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Menu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Menu[]    findAll()
 * @method Menu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    /**
     * @return array
     */
    public function getAllMenus():array
    {
        return $this->createQueryBuilder('m')

            ->orderBy('m.display_order', 'ASC')
            ->andWhere('m.niveau = :niveau OR m.niveau IS NULL')
            ->andWhere('m.type = :type')
            ->setParameter('niveau' , Menu::NIVEAU_MENU_1)
            ->setParameter('type', Menu::TYPE_MENU_CANDIDAT)
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /**
     * @return array
     */
    public function getAllFooters():array
    {
        return $this->createQueryBuilder('m')

            ->orderBy('m.display_order', 'ASC')
            ->andWhere('m.niveau = :niveau OR m.niveau IS NULL')
            ->andWhere('m.type = :type')
            ->setParameter('niveau' , Menu::NIVEAU_MENU_1)
            ->setParameter('type', Menu::TYPE_MENU_FOOTER)
            ->setMaxResults(2)
            ->getQuery()
            ->getResult()
            ;
    }


    /**
     * @param null $menu
     * @param null $niveau
     * @return array
     */
    public function getNiveauMenu($menu = null, $niveau = null ):array
    {
        return $this->createQueryBuilder('m')

            ->orderBy('m.display_order', 'ASC')
            ->andWhere('m.child_menu = :child_menu')
            ->andWhere('m.niveau = :niveau')
            ->setParameter('niveau' , $niveau)
            ->setParameter('child_menu' , $menu)
            //->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }
}
