<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @param $userId
     * @return mixed
     */
    public function getRecruteurs($userId): mixed
    {
        $user = $this->_em->getRepository("App:User")->find($userId);
        if($user->isSuperAdmin()){
            return $this->createQueryBuilder('u')
                ->orderBy('u.id', 'ASC')
                ->getQuery()
                ->getResult()
                ;
        }elseif ($user->isSuperRecruteur()){
            $query = $this->createQueryBuilder('u');

            $ids = array();
            if (count($user->getRecruteursEntreprise())>0){


            foreach ($user->getRecruteursEntreprise() as $item){
                $ids[] = $item->getId();
            }

            $query->orderBy('u.id', 'ASC')
                ->innerJoin('u.entreprises', 'e','WITH', $query->expr()->in('e.id', $ids))
            ;

            return $query->getQuery()->getResult() ;
            }else{
                return null;
            }
        }else{
            return null;
        }
    }

    /**
     * @param int $longueur
     * @return string
     */
    public function genererMDP($longueur = 8): string
    {
        $mdp = "";

        $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

        $longueurMax = strlen($possible);

        if ($longueur > $longueurMax) {
            $longueur = $longueurMax;
        }

        $i = 0;

        while ($i < $longueur) {
            $caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);


            if (!strstr($mdp, $caractere)) {
                $mdp .= $caractere;
                $i++;
            }
        }
        return $mdp;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
