<?php

namespace App\Repository;

use App\Entity\Equipe;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Equipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipe[]    findAll()
 * @method Equipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipe::class);
    }

    // /**
    //  * @return Equipe[] Returns an array of Equipe objects
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
    public function findEquipeByProjet(Utilisateur $utilisateur)
    {   
         $query=$this
         ->createQueryBuilder('e')
         ->select('e','p')
         ->join('e.membre','u')
         ->andWhere('e.membre = :$utilisateur');
         
         //if()
        return 
          //$this->createQueryBuilder('p')
            //->andWhere('p.exampleField = :val')
           // ->setParameter('val', $value)
            //->orderBy('p.id', 'ASC')
          //  ->setMaxResults(10)
         
            $query->getQuery()
            ->getResult()
        /*return $this->getEntityManager()
        ->createQuery('SELECT e FROM AppBundle:Equipe e where e.equipe.findOneEquipe()=?1')
        ->getResult()*/
        ;
    }

    /*
    public function findOneBySomeField($value): ?Equipe
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
