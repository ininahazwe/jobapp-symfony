<?php

namespace App\Repository;

use App\Entity\Dictionnaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Dictionnaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dictionnaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dictionnaire[]    findAll()
 * @method Dictionnaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DictionnaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dictionnaire::class);
    }

    public function getContractTypeList()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->setParameter('type', Dictionnaire::TYPE_CONTRACT);

        return $query->getQuery();
    }

    public function getDiplomeTypeList()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->setParameter('type', Dictionnaire::TYPE_DIPLOMA);

        return $query->getQuery()->getResult();
    }

    public function getLocationsList()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->setParameter('type', Dictionnaire::TYPE_LOCATION);

        return $query->getQuery();
    }
    public function getLocationsList2()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->andWhere('d.id != :id')
            ->setParameter('type', Dictionnaire::TYPE_LOCATION)
            ->setParameter('id', 330);

        return $query->getQuery()
            ->getResult();
    }

    public function getRegions()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->setParameter('type', Dictionnaire::TYPE_REGION)
        ;

        return $query->getQuery()
            ->getResult();
    }

    public function getCouleurs()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->setParameter('type', Dictionnaire::TYPE_SOL);

        return $query->getQuery()->getResult();
    }


    public function getPersonnages()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->setParameter('type', Dictionnaire::TYPE_PERSONNAGE);

        return $query->getQuery()->getResult();
    }


    public function getcomptoires()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->setParameter('type', Dictionnaire::TYPE_COMPTOIRE);

        return $query->getQuery()->getResult();
    }


    public function getMobiliers()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->setParameter('type', Dictionnaire::TYPE_MOBILIER);

        return $query->getQuery()->getResult();
    }


    public function getPlantes()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->setParameter('type', Dictionnaire::TYPE_PLANTE);

        return $query->getQuery()->getResult();
    }

    public function getMots()
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->orderBy('d.value', 'ASC')
            ->setParameter('type', Dictionnaire::TYPE_MOT_SOURCING);
        return $query->getQuery()->getResult();
    }

    /**
     * Get quiz results for selected job and user
     *
     * @param $text
     *
     * @return mixed
     */
    public function getOneLoacation($text)
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->andWhere('d.value LIKE :value')
            ->setParameter('type', Dictionnaire::TYPE_LOCATION)
            ->setParameter('value', $text. '%')
            ->setMaxResults(1)
        ;


        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * Get parent list of categorie
     * @param $commune
     * @return array
     */
    public function getOneCommune($commune)
    {
        $query = $this->getEntityManager()->getRepository('NewnetCoreBundle:Commune')->createQueryBuilder('c')
            ->select('c')
            ->where('c.commune LIKE :commune OR c.acheminement LIKE :commune')
            ->setMaxResults(1)
            ->setParameter('commune', '%' . $commune . '%');

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * Get parent list of categorie
     * @param $category
     * @return array
     */
    public function getOneCategory($metier): array
    {
        $query = $this->getEntityManager()->getRepository('NewnetCoreBundle:Category')->createQueryBuilder('c')
            ->select('c')
            ->where('c.title LIKE :title ')
            ->setMaxResults(1)
            ->setParameter('title', '%' . $metier . '%');

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * Get parent list of categorie
     *
     * @param $category
     *
     * @return array
     */
    public function getAllParent($category)
    {
        $query = $this->getEntityManager()->getRepository('NewnetCoreBundle:Category')->createQueryBuilder('c')
            ->select('c')
            ->where('c.parent = :parent')
            ->setParameter('parent', $category);

        return $query->getQuery()
            ->getResult();
    }

    /**
     * Get parent list of categorie
     * @param $category
     * @return array
     */
    public function getAllParent2($category): array
    {
        $query = $this->getEntityManager()->getRepository('NewnetCoreBundle:Category')->createQueryBuilder('c')
            ->select('c')
            ->where('c.parent = :parent')
            ->orWhere('c.id = :id')
            ->setParameter('parent', $category)
            ->setParameter('id', $category);

        return $query->getQuery()
            ->getResult();
    }

    /**
     * Get all categorie list of categorie
     * @return array
     */
    public function getAllCategorie(): array
    {
        $query = $this->getEntityManager()->getRepository('NewnetCoreBundle:Category')->createQueryBuilder('c')
            ->select('c')
            ->where('c.parent IS NOT NULL')
            ->andWhere('c.status = 1')
            ->orderBy('c.lft', 'ASC')   ;

        return $query->getQuery()
            ->getResult();
    }

    /**
     * Get all categorie list of categorie
     * @return array
     */
    public function getAllCategorieOrder(): array
    {
        /*$repository = $this->getEntityManager()->getRepository('NewnetCoreBundle:Category');
        $query = $repository->createQueryBuilder('c')
            ->select('c')
            ->where('c.parent IS NULL')
            ->getQuery()->getSingleResult();
        return $repository-> getChildrenQueryBuilder($query);*/

        $query = $this->getEntityManager()->getRepository('NewnetCoreBundle:Category')->createQueryBuilder('c')
            ->select('c')
            ->where('c.parent IS NOT NULL')
            ->andWhere('c.status = 1')
            ->orderBy('c.lft', 'ASC')
        ;

        return $query->getQuery()
            ->getResult();
    }

    /**
     * Get quiz results for selected job and user
     * @param $text
     * @return mixed
     */
    public function getOneEntite($text): mixed
    {
        $query = $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.type = :type')
            ->andWhere('d.value LIKE :value OR d.content LIKE :value')
            ->setParameter('type', Dictionnaire::TYPE_ENTITE)
            ->setParameter('value', $text. '%');



        return $query->getQuery()->getOneOrNullResult();
    }


    /**
     * Get all categorie list of categorie
     * @return array
     */
    public function getAllParentsCategorie(): array
    {
        $query = $this->getEntityManager()->getRepository('NewnetCoreBundle:Category')->createQueryBuilder('c')
            ->select('c')
            ->where('c.parent IS NOT NULL')
            ->andWhere('c.status = 1')
            ->andWhere('c.lvl = 1')
            ->orderBy('c.lft', 'ASC')   ;

        return $query->getQuery()
            ->getResult();
    }
}
