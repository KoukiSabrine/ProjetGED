<?php

namespace App\Repository;
//namespace AppBundle\Repository;

use App\Entity\Equipe;
use App\Entity\Projet;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Projet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Projet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Projet[]    findAll()
 * @method Projet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }

     /**
      * @return Projet[] Returns an array of Projet objects
      */



      /*public function findOneEquipe($id)
      {
        $qb = $this->createQueryBuilder('e');
       
        $qb->where('e.id = :id')
             ->setParameter('id', $id);
       
        return $qb
             ->getQuery()
             ->getResult() ;
      }*/



      public function getProjetsByUserId($x)  {
        $em = $this->getEntityManager();
        $rsm = new ResultSetMapping();
        $sql2 = "SELECT DISTINCT  p.id , p.titre ,p.etat , p.niveau ,p.duree_prevue,p.created_at ,p.date_lancement FROM projet as p 
               INNER JOIN equipe as e ON p.id=e.projet_id 
               INNER JOIN equipe_utilisateur as m ON m.equipe_id=e.id 
               WHERE m.utilisateur_id = ?";
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('titre', 'titre');
        $rsm->addScalarResult('etat', 'etat');
        $rsm->addScalarResult('niveau', 'niveau');
        $rsm->addScalarResult('duree_prevue', 'dureePrevue');
        $rsm->addScalarResult('created_at', 'createdAt');
        $rsm->addScalarResult('date_lancement', 'dateLancement');
        $query = $em->createNativeQuery($sql2, $rsm);
        $query->setParameter(1, $x);        
        return $query->getResult();
    }

    public function getEquipesByProjetId($x)  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql=" SELECT e.id,e.nom,e.gerant_id FROM equipe as e
           INNER JOIN projet as p ON p.id=e.projet_id 
           WHERE p.id = ? " ;
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('nom', 'nom');
      $rsm->addScalarResult('gerant_id', 'gerant_id');

      //$rsm->addScalarResult('u.nom', 'ger');
      //$rsm->addScalarResult('gerant', 'gerant.nom');

      $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1,$x);        
        return $query->getResult();

    }
   

    public function getProjetIdByUserId($x)  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql2 = "SELECT p.id  FROM projet as p 
             INNER JOIN equipe as e ON p.id=e.projet_id 
             INNER JOIN equipe_utilisateur as m ON m.equipe_id=e.id 
             WHERE m.utilisateur_id = ? ";
      $rsm->addScalarResult('id', 'id');
     
      $query = $em->createNativeQuery($sql2, $rsm);
      $query->setParameter(1, $x);        
      return $query->getResult();
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

    public function getRepertoiresByEquipeId($x)  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql=" SELECT r.id,r.nom FROM repertoire as r
           INNER JOIN equipe as e ON e.id=r.equipe_id 
           WHERE e.id = ?" ;
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('nom', 'nom');
     
      //$rsm->addScalarResult('gerant', 'gerant.nom');

      $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1,$x);        
        return $query->getResult();

    }



    
     
    

    
    /*$projets=array();
    public function findProjetByUser2($utilisateur)
    
    {   
        $equipes=$utilisateur->getEquipes();
      foreach ($equipes as $equipe) {
          $pr=$equipe->getProjet();
          $projets->setProjet($pr);
          
      }
      return $projets;
      
    }*/
   
    

    
    

    /*
    public function findOneBySomeField($value): ?Projet
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
