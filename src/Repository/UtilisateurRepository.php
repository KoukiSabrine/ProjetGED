<?php

namespace App\Repository;

use App\Entity\Equipe;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method Utilisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utilisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utilisateur[]    findAll()
 * @method Utilisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtilisateurRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }




    public function getUsersByEquipeId($x)  {
        $em = $this->getEntityManager();
        $rsm = new ResultSetMapping();
        $sql=" SELECT u.id,u.nom,u.prenom FROM utilisateur as u
             INNER JOIN equipe_utilisateur as e ON e.utilisateur_id=u.id
             INNER JOIN equipe as e2 ON e.equipe_id=e2.id
             WHERE e2.id = ?" ;
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('nom', 'nom');
        $rsm->addScalarResult('prenom', 'prenom');
        //$rsm->addScalarResult('gerant', 'gerant.nom');
  
        $query = $em->createNativeQuery($sql, $rsm);
          $query->setParameter(1,$x);        
          return $query->getResult();
  
      }


      public function findUsers($id)
      {
          $qb = $this->createQueryBuilder('u');
        //    $qb->innerJoin(Utilisateur::class, 'u1', Join::WITH, 'u1.id = :equipe_utilisateur.utilisateur_id');
        //    $qb->innerJoin(Equipe::class, 'e', Join::WITH, 'e.id = :equipe_utilisateur.equipe_id');

         /*$qb->select('u.id,u.nom,u.prenom ');
         $qb->from('utilisateur', 'u');
         $qb->innerJoin(Utilisateur::class, 'u1', Join::WITH, 'u1 = :equipe_utilisateur.utilisateur_id');
         $qb->innerJoin(Equipe::class, 'e', Join::WITH, 'equipe_utilisateur.equipe_id = :e');*/

         

        //   $qb->where('equipe.id = :id')
        //         ->setParameter('id', $id);
          return $qb;
      } 

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof Utilisateur) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // /**
    //  * @return Utilisateur[] Returns an array of Utilisateur objects
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
    public function findOneBySomeField($value): ?Utilisateur
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
