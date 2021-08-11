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


    public function getEquipesByUserId($x)  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql2 = "SELECT DISTINCT e.id,e.nom_eq,e.projet_id FROM equipe as e
             
             INNER JOIN equipe_utilisateur as m ON m.equipe_id=e.id 
             WHERE m.utilisateur_id = ?";
       $rsm->addScalarResult('id', 'id');
       $rsm->addScalarResult('nom_eq', 'nom');
       $rsm->addScalarResult('prenom', 'gerant');
      //  $rsm->addScalarResult('titre', 'titre');
      $query = $em->createNativeQuery($sql2, $rsm);
      $query->setParameter(1, $x);        
      return $query->getResult();
  }


  public function getRepertoiresByUserId($x)  {
    $em = $this->getEntityManager();
    $rsm = new ResultSetMapping();
    $sql=" SELECT r.id,r.nom,r.equipe_id,repertoire_id,e.nom_eq FROM repertoire as r
         INNER JOIN equipe as e ON e.id=r.equipe_id 
         INNER JOIN equipe_utilisateur as m ON m.equipe_id=e.id 

         WHERE m.utilisateur_id = ?" ;
    $rsm->addScalarResult('id', 'id');
    $rsm->addScalarResult('nom', 'name');
    $rsm->addScalarResult('equipe_id', 'equipe');
    $rsm->addScalarResult('repertoire_id', 'repertoire');
      $rsm->addScalarResult('nom_eq', 'nomEq');

   
    //$rsm->addScalarResult('gerant', 'gerant.nom');

    $query = $em->createNativeQuery($sql, $rsm);
      $query->setParameter(1,$x);        
      return $query->getResult();

  }

  public function getDocumentsByUserId($x)  {
    $em = $this->getEntityManager();
    $rsm = new ResultSetMapping();
    $sql=" SELECT d.id,d.nom,d.repertoire_id,d.url,d.file FROM document as d
         INNER JOIN repertoire as r ON r.id=d.repertoire_id 
         INNER JOIN equipe_utilisateur as m ON m.equipe_id=r.equipe_id
         
         WHERE m.utilisateur_id = ?" ;
    $rsm->addScalarResult('id', 'id');
    $rsm->addScalarResult('nom', 'nom');
    $rsm->addScalarResult('repertoire_id', 'repertoire_id');
    $rsm->addScalarResult('url', 'url');
    $rsm->addScalarResult('file', 'file');
    
   
    //$rsm->addScalarResult('gerant', 'gerant.nom');

    $query = $em->createNativeQuery($sql, $rsm);
      $query->setParameter(1,$x);        
      return $query->getResult();

  }

  


   

    public function getUsers()  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql2 = "SELECT DISTINCT u.id,u.nom,u.prenom  FROM utilisateur as u
            
              ";
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('nom', 'nom');
      $rsm->addScalarResult('prenom', 'prenom');
      //$rsm->addScalarResult('niveau', 'niveau');
      // $rsm->addScalarResult('created_at', 'createdAt');
      // $rsm->addScalarResult('date_lancement', 'dateLancement');
      // $rsm->addScalarResult('duree_prevue', 'dureePrevue');
  
  
  
     
      $query = $em->createNativeQuery($sql2, $rsm);
           
      return $query->getResult();
  }

    public function getEquipes()  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql=" SELECT e.id,e.nom_eq,u.prenom ,e.projet_id,p.titre FROM equipe as e
      INNER JOIN projet as p ON p.id=e.projet_id 
       INNER JOIN utilisateur as u ON u.id=e.gerant_id "
          ;
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('nom_eq', 'nom');
      $rsm->addScalarResult('prenom', 'gerant');
      $rsm->addScalarResult('titre', 'titre');

      //$rsm->addScalarResult('u.nom', 'ger');
      //$rsm->addScalarResult('gerant', 'gerant.nom');

      $query = $em->createNativeQuery($sql, $rsm);
               
        return $query->getResult();

    }

    public function getDocuments()  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql2 = "SELECT DISTINCT d.id,d.nom FROM document as d
            
              ";
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('nom', 'nom');
      
  
  
  
     
      $query = $em->createNativeQuery($sql2, $rsm);
           
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



  
  public function getProjets()  {
    $em = $this->getEntityManager();
    $rsm = new ResultSetMapping();
    $sql2 = "SELECT DISTINCT p.id,p.titre,p.etat,p.niveau,p.created_at,p.date_lancement,p.duree_prevue  FROM projet as p 
          
            ";
    $rsm->addScalarResult('id', 'id');
    $rsm->addScalarResult('titre', 'titre');
    $rsm->addScalarResult('etat', 'etat');
    $rsm->addScalarResult('niveau', 'niveau');
    $rsm->addScalarResult('created_at', 'createdAt');
    $rsm->addScalarResult('date_lancement', 'dateLancement');
    $rsm->addScalarResult('duree_prevue', 'dureePrevue');



   
    $query = $em->createNativeQuery($sql2, $rsm);
         
    return $query->getResult();
}
  

public function getProjetsByEtat()  {
  $em = $this->getEntityManager();
  $rsm = new ResultSetMapping();
  $sql2 = "SELECT distinct p.id,p.titre,p.etat,p.niveau,p.created_at,p.date_lancement,p.duree_prevue  FROM projet as p 
         
         WHERE p.etat = 'en cours' ";
  $rsm->addScalarResult('id', 'id');
  $rsm->addScalarResult('titre', 'titre');
  $rsm->addScalarResult('etat', 'etat');
  $rsm->addScalarResult('niveau', 'niveau');
  $rsm->addScalarResult('created_at', 'createdAt');
  $rsm->addScalarResult('date_lancement', 'dateLancement');
  $rsm->addScalarResult('duree_prevue', 'dureePrevue');



 
  $query = $em->createNativeQuery($sql2, $rsm);
       
  return $query->getResult();
}


public function getProjetsByEtat2()  {
  $em = $this->getEntityManager();
  $rsm = new ResultSetMapping();
  $sql2 = "SELECT distinct p.id,p.titre,p.etat,p.niveau,p.created_at,p.date_lancement,p.duree_prevue  FROM projet as p 
         
         WHERE p.etat = 'terminé' ";
  $rsm->addScalarResult('id', 'id');
  $rsm->addScalarResult('titre', 'titre');
  $rsm->addScalarResult('etat', 'etat');
  $rsm->addScalarResult('niveau', 'niveau');
  $rsm->addScalarResult('created_at', 'createdAt');
  $rsm->addScalarResult('date_lancement', 'dateLancement');
  $rsm->addScalarResult('duree_prevue', 'dureePrevue');



 
  $query = $em->createNativeQuery($sql2, $rsm);
       
  return $query->getResult();
}

public function getProjetsByEtat3()  {
  $em = $this->getEntityManager();
  $rsm = new ResultSetMapping();
  $sql2 = "SELECT distinct p.id,p.titre,p.etat,p.niveau,p.created_at,p.date_lancement,p.duree_prevue  FROM projet as p 
         
         WHERE p.etat = 'début' ";
  $rsm->addScalarResult('id', 'id');
  $rsm->addScalarResult('titre', 'titre');
  $rsm->addScalarResult('etat', 'etat');
  $rsm->addScalarResult('niveau', 'niveau');
  $rsm->addScalarResult('created_at', 'createdAt');
  $rsm->addScalarResult('date_lancement', 'dateLancement');
  $rsm->addScalarResult('duree_prevue', 'dureePrevue');



 
  $query = $em->createNativeQuery($sql2, $rsm);
       
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

    public function getEquipeByProjetId($x)  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql=" SELECT e.id,e.nom_eq,u.prenom,p.titre,u.nom,u.prenom FROM equipe as e
           INNER JOIN projet as p ON p.id=e.projet_id 
           INNER JOIN utilisateur as u ON u.id=e.gerant_id
           WHERE p.id = ?" ;
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('nom_eq', 'nomEq');
      $rsm->addScalarResult('titre', 'projet');
      $rsm->addScalarResult('prenom', 'gerant');
      $rsm->addScalarResult('nom', 'nom');
      

        

     
      //$rsm->addScalarResult('gerant', 'gerant.nom');

      $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1,$x);        
        return $query->getResult();

    }

    public function getRepertoiresByEquipeId($x)  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql=" SELECT r.id,r.nom,r.equipe_id,repertoire_id,e.nom_eq FROM repertoire as r
           INNER JOIN equipe as e ON e.id=r.equipe_id 
           WHERE e.id = ?" ;
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('nom', 'name');
      $rsm->addScalarResult('equipe_id', 'equipe');
      $rsm->addScalarResult('repertoire_id', 'repertoire');
        $rsm->addScalarResult('nom_eq', 'nomEq');

     
      //$rsm->addScalarResult('gerant', 'gerant.nom');

      $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1,$x);        
        return $query->getResult();

    }
    public function getRepertoiresByEtat($x)  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql=" SELECT r.id,r.nom,r.equipe_id,repertoire_id,e.nom_eq FROM repertoire as r
           INNER JOIN equipe as e ON e.id=r.equipe_id 
           WHERE e.id = ?" ;
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('nom', 'name');
      $rsm->addScalarResult('equipe_id', 'equipe');
      $rsm->addScalarResult('repertoire_id', 'repertoire');
        $rsm->addScalarResult('nom_eq', 'nomEq');

     
      //$rsm->addScalarResult('gerant', 'gerant.nom');

      $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1,$x);        
        return $query->getResult();

    }


    public function getDocumentsByRepertoireId($x)  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql=" SELECT d.id,d.nom,d.repertoire_id,d.url,d.file FROM document as d
           INNER JOIN repertoire as r ON r.id=d.repertoire_id 
           WHERE r.id = ?" ;
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('nom', 'nom');
      $rsm->addScalarResult('repertoire_id', 'repertoire_id');
      $rsm->addScalarResult('url', 'url');
      $rsm->addScalarResult('file', 'file');
      
     
      //$rsm->addScalarResult('gerant', 'gerant.nom');

      $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1,$x);        
        return $query->getResult();

    }


    public function getHistoriqueByDocumentId($x)  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql=" SELECT h.id,h.date_modif,h.aut_id,h.version_doc,h.remarque,h.etat_doc,h.file_his ,u.nom,u.prenom FROM historique as h
           INNER JOIN document as d ON d.id=h.document_id 
           INNER JOIN utilisateur as u ON u.id=h.aut_id

           WHERE d.id = ? " ;
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('date_modif', 'date_modif');
      $rsm->addScalarResult('aut_id', 'aut_id');
      $rsm->addScalarResult('version_doc', 'version');
      $rsm->addScalarResult('remarque', 'remarque');
      $rsm->addScalarResult('etat_doc', 'etat');
      $rsm->addScalarResult('file_his', 'file');

      $rsm->addScalarResult('nom', 'nom');
      $rsm->addScalarResult('prenom', 'prenom');




      //$rsm->addScalarResult('gerant_id', 'gerant_id');

      //$rsm->addScalarResult('u.nom', 'ger');
      //$rsm->addScalarResult('gerant', 'gerant.nom');

      $query = $em->createNativeQuery($sql, $rsm);
        $query->setParameter(1,$x);        
        return $query->getResult();

    }


    public function getCommentaireByDocumentId($x)  {
      $em = $this->getEntityManager();
      $rsm = new ResultSetMapping();
      $sql=" SELECT c.id,c.comment FROM commentaire as c
           INNER JOIN document as d ON d.id=c.document_id 
           WHERE d.id = ? " ;
      $rsm->addScalarResult('id', 'id');
      $rsm->addScalarResult('comment', 'comment');
      //$rsm->addScalarResult('gerant_id', 'gerant_id');

      //$rsm->addScalarResult('u.nom', 'ger');
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
