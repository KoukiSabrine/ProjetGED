<?php


namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Document;
use App\Entity\Equipe;
use App\Entity\File;
use App\Entity\Historique;
use App\Entity\Utilisateur;
use App\Entity\Projet;
use App\Entity\Repertoire;
use App\Entity\Tag;
use App\Form\AffectToEquipeType;
use App\Form\CommentaireType;
use App\Form\DeleteUserFromTeamType;
use App\Form\RepertoireType;
use App\Form\DocumentType;
use App\Form\EditDocumentType;
use App\Form\EditEquipeType;
use App\Form\EditProjetType;
use App\Form\EquipeType;
use App\Form\HistoriqueType;
use App\Form\ProjetType;
use App\Form\TagType;
use App\Form\UtilisateurType;
use App\Repository\UtilisateurRepository;
use App\Repository\ProjetRepository;
use App\Repository\EquipeRepository;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Services\ApiService;
use App\Services\AuthService;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf ;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

/**
* @Route("/utilisateur")
*/
class UtilisateurController extends AbstractController
{


 
 /**
     * @Route("/showProjetEnCours", name="Liste_Projets_Etat", methods={"GET"})
     *
     */
    public function showProjetByEtat(ProjetRepository $projetRepository,Session $session): Response
    {
       
        
         $list=$projetRepository->getProjetsByEtat();
         $session->set('projets',$list);
         
         

        return $this->render('projet/projetListEtat.html.twig',  [
                'projetsEtat' =>$list]); 
                     
    }


    /**
     * @Route("/showProjetTerminés", name="Liste_Projets_Etat2", methods={"GET"})
     *
     */
    public function showProjetByEtat2(ProjetRepository $projetRepository,Session $session): Response
    {
       
        
         $list=$projetRepository->getProjetsByEtat2();
         $session->set('projetsTerminés',$list);
         //dd($list);
         

        return $this->render('projet/projetListTerminés.html.twig',  [
                'projetsTerminés' =>$list]); 
                     
    }

    
    /**
     * @Route("/showProjetDébut", name="Liste_Projets_Etat3", methods={"GET"})
     *
     */
    public function showProjetByEtat3(ProjetRepository $projetRepository,Session $session): Response
    {
       
        
         $list=$projetRepository->getProjetsByEtat3();
         $session->set('projetsDébut',$list);
         //dd($list);
         

        return $this->render('projet/projetListDébut.html.twig',  [
                'projetsDébut' =>$list]); 
                     
    }


      
        /**
        * @Route("/", name="utilisateur_index", methods={"GET"})
        */
        public function index(UtilisateurRepository $utilisateurRepository,ProjetRepository $projetRepository, Session $session): Response
        {
                //besoin de droits admin
                //$list=$session->get('projets');
                $listEnCours=$projetRepository->getProjetsByEtat();

                //$list2=$session->get('projetsTerminés');
                $listTerminés=$projetRepository->getProjetsByEtat2();

               // $list3=$session->get('projetsDébut');
               $listDebut=$projetRepository->getProjetsByEtat3();
               $listUsers=$projetRepository->getUsers();
               $listDocs=$projetRepository->getDocuments();
               $equipes=$projetRepository->getEquipes();
               
               
        
               $listPr=$projetRepository->getProjetsByUserId( $this->getUser()->getId());
               $listEq=$projetRepository->getEquipesByUserId($this->getUser()->getId());
               $listRep=$projetRepository-> getRepertoiresByUserId($this->getUser()->getId());
               $docs=$projetRepository-> getDocumentsByUserId($this->getUser()->getId());
               //$users=$projetRepository->getUsers();
               

              
               
               

               //dd($listUsers);


         //dd($list2);
                 $session->set('projets',$listEnCours);
              
                $utilisateur = $this->getUser();
                if(!$utilisateur)
                {
                        $session->set("message", "Merci de vous connecter");
                        return $this->redirectToRoute('app_login');
                }

                else if(in_array('ROLE_ADMIN', $utilisateur->getRoles())){
                      /* return $this->render('utilisateur/index.html.twig', [
                                'utilisateurs' => $utilisateurRepository->findAll(),
                        ]);*/
                        return $this->render('navigation/admin.html.twig',  [
                                'projets' =>$listEnCours ,'projetsTerminés' =>$listTerminés
                                ,'projetsDébut' =>$listDebut ,'users'=>$listUsers  ,'docs'=>$listDocs,'equipes'=>$equipes]);
                }

                 return $this->render('navigation/membre.html.twig',  [
                        'projetsByUser' => $listPr,'equipesByUser' =>$listEq ,'repsByUser' =>$listRep,'docsByUser' =>$docs ]);

                  //return $this->render('navigation/membre.html.twig');
        }





         /*****************     Projet      ********************/

          /**
     * @Route("/showProjet", name="Liste_Projets", methods={"GET"})
     *
     */
    public function showProjet(ProjetRepository $projetRepository): Response
    {
        $id = $this->getUser()->getId();
        
        $list=$projetRepository->getProjetsByUserId($id);
        return $this->render('projet/projetList.html.twig',  [
                'projets' =>$list   ]); 

            
    }


        /**
     * @Route("/showProjet2", name="Liste_Projets_Admin", methods={"GET"})
     *
     */
    public function showProjetAdmin(ProjetRepository $projetRepository): Response
    {
       
        
         $list=$projetRepository->getProjets();
       

        return $this->render('projet/projetListAdmin.html.twig',  [
                'projets' =>$list]); 
         

            
    }


    /**
 * @Route("/projet/newProjet",name="projet_new")
 * @param Request $request
 * @return Response
 */

public function newProjet(Request $request,Session $session): Response
{
  $projet=new Projet();
  $form=$this->createForm(ProjetType::class,$projet);
  $form->handleRequest($request);

//   $equipe=$session->get('equipe');

  if($form->isSubmitted() && $form->isValid()){
        $createdAt= $this->startDate = new \DateTime();
          
     
//       $rep = $this->getDoctrine()->getRepository(Equipe::class);
//      $pp=$rep->findOneBy(["id"=>$equipe->getId()]);

    

     $projet->setCreatedAt($createdAt);
     $projet->setEtat('Début');
     $projet->setNiveau('0');

    
     
          
      $em=$this->getDoctrine()->getManager();
    
      $em->persist($projet);
     
      $em->flush();
                         

      return $this->redirectToRoute('Liste_Projets_Admin');
    
      
  }


return $this->render('projet/addProjet.html.twig',[
  'form'=>$form->createView()
]);
}



 /**
 * @Route("/{id}/projetEdit",name="edit_projet")
 * @param Request $request
 * @return Response
 */

public function editProjet(Request $request,Session $session,Projet $prj): Response
 {
        //dd($session->get('docum')->getId());
//        $doc = $this->getDoctrine()->getRepository(Document::class);
//        $pr=$doc->findOneBy(["id"=>$id]);

        
        //dd($prj);
  $projet=new Projet();
  $form=$this->createForm(EditProjetType::class,$projet);
  $form->handleRequest($request);
 
 
  if($form->isSubmitted() && $form->isValid()){
      

        //$file=$form->get('document')->get('file')->getData();

        $n1=$form->get('niveau')->getData();
        $e1=$form->get('etat')->getData();
        //dd($e1);
        // $aut=$this->getUser();
        // $remarque=$form->get('remarque')->getData();
       
       //$pr->setFile($file);
  

        
        $prj->setEtat($e1);
        $prj->setNiveau($n1);
       

   //dd($histo);
          
      $em=$this->getDoctrine()->getManager();
    
      $em->persist($prj);
      //dd('ok');  
      $em->flush();
            

      return $this->redirectToRoute('Liste_Projets_Admin');
   
      
  }


return $this->render('projet/editProjet.html.twig',[
  'form'=>$form->createView()
]);



}

/**
     * @Route("/{id}/deleteProjet",name="projet_delete")
     * @param Request $request
     */

    public function deleteProjet(Request $request,Session $session,Projet $projet): Response
      {
                        
                       
                        
                        //$session->set('document',$document);
                         //dd($session);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($projet);
                        //dd('ok');
                        $entityManager->flush();
                       
                        return $this->redirectToRoute('Liste_Projets_Admin');
                                
                        //}
                        //return $this->redirectToRoute('docoment/documentList.html.twig');

                        
      }






      

    
         /*****************     Equipe      ********************/


          /**
     * @Route("/{id}/showEquipe", name="Liste_Equipes", methods={"GET"})
     *
     */
       public function showEquipe(Projet $projet,Session $session,ProjetRepository $projetRepository): Response
    {       
        //$list = $projetRepository->getEquipeByProjetId($projet->getId());
        $list=$projet->getEquipe();
        //$listUsers = $projetRepository->getUsersByEquipeId(1);

        
        
       // $session->set('list',$list);
        //dd($session->get('list'));
        $utilisateur = $this->getUser();
        //dd($utilisateur);
        return $this->render('equipe/equipeList.html.twig',  [
                'equipes' =>  $list,
                'u' => $utilisateur
                
        ]);  
    }

      /**
     * @Route("/{id}/showEquipeAdmin", name="Liste_Equipes_Admin", methods={"GET"})
     *
     */
    public function showEquipeAdmin(Projet $projet,Session $session,ProjetRepository $projetRepository): Response
    {   $session->set('prAdmin',$projet);
        
        $list = $projetRepository->getEquipeByProjetId($projet->getId());
        
       // $session->set('list',$list);
        //dd($session->get('list'));
        return $this->render('equipe/equipeListAdmin.html.twig',  [
                'equipes' =>  $list
                
        ]);  
    }


   



    
    /**
 * @Route("/equipe/newEquipe",name="equipe_new")
 * @param Request $request
 * @return Response
 */

public function newEquipe(Request $request,Session $session): Response
{
        $projet=$session->get('prAdmin');
        //dd($projet);
  $equipe=new Equipe();
  $form=$this->createForm(EquipeType::class,$equipe);
 
  $form->handleRequest($request);
 
//   $equipe=$session->get('equipe');

  if($form->isSubmitted() && $form->isValid()){
            
               
      $pr = $this->getDoctrine()->getRepository(Projet::class);
     $pp=$pr->findOneBy(["id"=>$projet->getId()]);
//dd($pp);
    
     $equipe->setProjet($pp);
          
          
      $em=$this->getDoctrine()->getManager();
    
      $em->persist($equipe);
     
      $em->flush();
                         

      return $this->redirectToRoute('home');
    
      
  }

return $this->render('equipe/addEquipe.html.twig',[
  'form'=>$form->createView()
]);
}
    

/**
 * @Route("/{id}/affectUser",name="equipe_affect")
 * @param Request $request
 * @return Response
 */

public function affectUser(Request $request,Session $session,Equipe $equipe): Response
{
        $projet=$session->get('prAdmin');
        //dd($projet);
  $equipe=new Equipe();
  $form=$this->createForm(AffectToEquipeType::class,$equipe);
 
  $form->handleRequest($request);
 
//   $equipe=$session->get('equipe');

  if($form->isSubmitted() && $form->isValid()){
  
               
      $pr = $this->getDoctrine()->getRepository(Projet::class);
     $pp=$pr->findOneBy(["id"=>$projet->getId()]);
//dd($pp);
    
     $equipe->setProjet($pp);
     
          
      $em=$this->getDoctrine()->getManager();
    
      $em->persist($equipe);
     
      $em->flush();
                         

      return $this->redirectToRoute('home');
    
      
  }

return $this->render('equipe/addEquipe.html.twig',[
  'form'=>$form->createView()
]);
}
    


/**
 * @Route("/{id}/equipeEdit",name="edit_equipe")
 * @param Request $request
 * @return Response
 */

public function editEquipe(Request $request,Session $session,Equipe $eq): Response
 {
        //dd($session->get('docum')->getId());
//        $doc = $this->getDoctrine()->getRepository(Document::class);
//        $pr=$doc->findOneBy(["id"=>$id]);

        
       // dd($eq);
//        $m=$eq->getMembre();
       //dd($eq);
  $equipe=new Equipe();
  $form=$this->createForm(EditEquipeType::class,$equipe);
  $form->handleRequest($request);
  $o=$session->set('eqq',$eq);
  $eqq=$session->get('eqq');

  //dd($eqq);

 
  if($form->isSubmitted() && $form->isValid()){
      
        $gerant=$form->get('gerant')->getData();       
         $m=$form->get('membre')->getData();
        //dd($m);
        foreach($m as $m1){
                $eq->addMembre($m1);
        }
        //dd($eq->getMembre());
        $eq->setGerant($gerant);
        
              
         
      $em=$this->getDoctrine()->getManager();
    
      $em->persist($eq);
      //dd('ok');  
      $em->flush();
            
      return $this->redirectToRoute('home');
  
      
  }


return $this->render('equipe/editEquipe.html.twig',[
  'form'=>$form->createView()
]);

}


 /**
     * @Route("/{id}/deleteUserFromEq",name="userEq_delete")
     * @param Request $request
     */

    public function deleteUserFromEq(Request $request,Session $session,Equipe $eq): Response
      {
        $equipe=new Equipe();
       
        $form=$this->createForm(DeleteUserFromTeamType::class,$equipe);
        //dd('ok');      

        $form->handleRequest($request);
       
        // $rep = $this->getDoctrine()->getRepository(Equipe::class);
        // $pp=$rep->findOneBy(["id"=>$equipe->getId()]);
        $eqq=$session->get('eqq');
                //dd($eqq);

        $list=$eqq->getMembre();
        //$users=$eqq->get
        if($form->isSubmitted() && $form->isValid()){
            
        //       $gerant=$form->get('gerant')->getData();       
               $m=$form->get('membre')->getData();
              //dd($m);
              foreach($m as $m1){
                      $eq->removeMembre($m1);
              }
              //dd($eq->getMembre());
        //       $eq->setGerant($gerant);
              
                    
               
            $em=$this->getDoctrine()->getManager();
          
            $em->persist($eq);
            //dd('ok');  
            $em->flush();
                  
            return $this->render('utilisateur/show.html.twig',[
                'eq' =>$eqq,'users' =>$list
              ]);

                        
      }
      return $this->render('equipe/deleteUserFromEq.html.twig',[
        'form'=>$form->createView(),'eq' =>$eqq
      ]);
}


  /**
     * @Route("/{id}/deleteEquipe",name="equipe_delete")
     * @param Request $request
     */

    public function deleteEquipe(Request $request,Session $session,Equipe $equipe,ProjetRepository $projetRepository): Response
      {                              $projet=$session->get('prAdmin');
        $list = $projetRepository->getEquipeByProjetId($projet->getId());

                
                        
                        //$session->set('document',$document);
                         //dd($session);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($equipe);
                        //dd('ok');
                        $entityManager->flush();
                       
                        return $this->redirectToRoute('home');
                        // return $this->render('equipe/equipeListAdmin.html.twig',  [
                        //         'equipes' =>  $list
                                
                        // ]);  
                                
                        //}
                        //return $this->redirectToRoute('docoment/documentList.html.twig');
                        
      }









     /*****************     User      ********************/

          /**
     * @Route("/{id}/showUser", name="Liste_Users", methods={"GET"})
     *
     */

    public function showUser(ProjetRepository $projetRepository,Equipe $equipe): Response
    {       
        
        //$list=$projetRepository->getUsersByEquipeId(2);
        $list=$equipe->getMembre();
        
        return $this->render('utilisateur/show.html.twig',  [
                'users' =>  $list,'eq' =>$equipe
        ]);  
    }


      /**
     * @Route("/showUser", name="Liste_Users_Admin", methods={"GET"})
     *
     */

    public function showUsersAdmin(ProjetRepository $projetRepository): Response
    {       
        
        //$list=$projetRepository->getUsersByEquipeId(2);
        $list=$this->getDoctrine()->getRepository(Utilisateur::class)->findAll();
        return $this->render('utilisateur/show.html.twig',  [
                'users' =>  $list
        ]);  
    }



     /*****************     Repertoire       ********************/



      
    public function showRepJSON(ProjetRepository $projetRepository,Equipe $equipe):JsonResponse
    {       
        //$utilisateur = $this->getUser();
       
        
        $idEq=$equipe->getId();
     $list=$projetRepository->getRepertoiresByEquipeId($idEq);
     //$this->directoryList = $this->getAllObjects();

     //$json = json_encode($list, JSON_FORCE_OBJECT);   //arrayToJSON
     
    
        return new JsonResponse($list);
    }



          /**
     * @Route("/{id}/showRepertoire", name="Liste_Repertoires", methods={"GET"})
     *
     */

    public function showRepertoire(ProjetRepository $projetRepository,Equipe $equipe,Session $session): Response
    {       
        //dd($equipe->getMembre());
        $utilisateur = $this->getUser();
        //$session=$request->getSession();
        $idEq=$equipe->getId();
       


     //dd($equipe);
     $list=$projetRepository->getRepertoiresByEquipeId($idEq);
     
     //$repository = $this->getDoctrine()->getRepository(Equipe::class);

     //$tt=$repository->findOneBy(["id"=>5]);

     $session->set('equipe',$equipe);
     //dd($equipe);
     
     //dd($session->get('list'));
     //$json=$this->showRepJSON($projetRepository,$equipe);
    

     //$json = json_encode($list, JSON_FORCE_OBJECT);    //arrayToJSON
     $json = json_encode($list); 
    
     //$listSousRep=$repertoire->getRepertoire();
      
//      $idRep=$repertoire->getId();
//      $listDoc=$projetRepository->getDocumentsByRepertoireId($idRep);
   
        return $this->render('repertoire/repertoireList.html.twig',  [
                'repertoires' =>  $list,'zNodes' => $json,'equipe'=> $equipe,'user'=> $utilisateur
        ]);  
    }


/**
 * @Route("/repertoire/newRepertoire",name="repertoire_new")
 * @param Request $request
 * @return Response
 */

      public function newRepertoire(Request $request,Session $session): Response
      {
        $repertoire=new Repertoire();
        $sousRep=$repertoire->getRepertoire();
        $form=$this->createForm(RepertoireType::class,$repertoire);
        $form->handleRequest($request);
        //dd($form);
        $equipe=$session->get('equipe');
        

        //dd($equipe);
        if($form->isSubmitted() && $form->isValid()){
                
             //$ep=$form->getData();
             //dd($equipe);
            //$repertoire->setEquipe($equipe);
            $rep = $this->getDoctrine()->getRepository(Equipe::class);
           $pp=$rep->findOneBy(["id"=>$equipe->getId()]);

           //dd($rep);
    
           $repertoire->setEquipe($pp);
             

           $session->set('rep',$repertoire);
           //dd($session->get('rep'));
           $sr=new Repertoire();
           $sr->setEquipe($pp);
           $sr->setNom('ml');
           //dd($sr);
           //$session->set('sr',$sr);
           //$repertoire->setRepertoire($sr);
           //dd($session->get('sr'));
           

            //$repertoire->setRepertoire($sr);
            //$repertoire->addSousRepertoire($repertoire);
                      
                
            $em=$this->getDoctrine()->getManager();
          
            $em->persist($repertoire);
            //dd($repertoire);
            $em->flush();
                               

            return $this->redirectToRoute('home');
          
            
        }


return $this->render('repertoire/addRepertoire.html.twig',[
        'form'=>$form->createView()
    ]);
    }



    
    
        /****************       SousRepertoire      ****************/

    
          /**
     * @Route("/{id}/showSousRepertoire", name="Liste_SousRepertoires", methods={"GET"})
     *
     */

    public function showSousRepertoire(Repertoire $repertoire,ProjetRepository $projetRepository,Session $session): Response
    {       
     $utilisateur = $this->getUser();
     $reppp=$session->get('rep');
 //dd($reppp->getId());
     $listSousRep=$repertoire->getSousRepertoire();
     //dd( $listSousRep);
      //dd($session);
   $equipe=$session->get('equipe');
   //dd($equipe);
    /* $ss=$session->get('sr');
     dd($ss);

     $rep = $this->getDoctrine()->getRepository(Repertoire::class);
     $pp=$rep->findOneBy(["id"=>$ss->getId()]);
     dd($pp);
     $listSousRep->addSousRepertoire($pp);*/
     


   
        return $this->render('sousRepertoire/sousRepertoireList.html.twig',  [
             'sousRepertoires'=> $listSousRep,'equipe' =>$equipe,'user' =>$utilisateur
        ]);  
    }




    /**
 * @Route("/repertoire/newSousRepertoire",name="sous_repertoire_new")
 * @param Request $request
 * @return Response
 */

public function newSousRepertoire(Request $request,Session $session): Response
{
  $repertoire=new Repertoire();
  $sousRep=$repertoire->getRepertoire();
  $form=$this->createForm(RepertoireType::class,$repertoire);
  $form->handleRequest($request);
  //dd($form);
  $equipe=$session->get('equipe');
  $reppp=$session->get('repertoire');
  //dd($reppp);
  if($form->isSubmitted() && $form->isValid()){
          
       //$ep=$form->getData();
       //dd($equipe);
      //$repertoire->setEquipe($equipe);
      $rep = $this->getDoctrine()->getRepository(Equipe::class);
     $pp=$rep->findOneBy(["id"=>$equipe->getId()]);

     //dd($rep);

     $repertoire->setEquipe($pp);
     //->setRepertoire($reppp);
     //$reppp->setRepertoire($repertoire);
//      $session->set('rep',$repertoire);
     //dd($session);
//      $sr=new Repertoire();
//      $sr->setEquipe($pp);
//      $sr->setNom('ml');
     //dd($sr);
     //$session->set('sr',$sr);
     //$repertoire->setRepertoire($sr);
     //dd($session->get('sr'));
     
   
      //$repertoire->setRepertoire($sr);
      //dd($repertoire);
      $reppp->addSousRepertoire($repertoire);
      dd('ok');
      //dd($repertoire->getSousRepertoire()) ;   
       //dd($reppp->getSousRepertoire()) ;
       //dd($reppp);  
      $em=$this->getDoctrine()->getManager();
    
      $em->persist($reppp);
    //dd($reppp);
    //$em->clear($reppp);
      $em->flush();
                         

      return $this->redirectToRoute('home');
    
      
  }


return $this->render('repertoire/addRepertoire.html.twig',[
  'form'=>$form->createView()
]);
}
    


    /**
     * @Route("/{id}/deleteRepertoire",name="repertoire_delete")
     * @param Request $request
     */

    public function deleteRepertoire(Repertoire $repertoire): Response
      {
                        
                       
                        
                        //$session->set('document',$document);
                         //dd($session);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($repertoire);
                        //dd('ok');
                        $entityManager->flush();
                       
                        return $this->redirectToRoute('home');
                                
                        //}
                        //return $this->redirectToRoute('docoment/documentList.html.twig');

                        
      }


    




          /************ Document **************/


  /**
     * @Route("/{id}/showDocument", name="Liste_Documents", methods={"GET"})
     *
     */

public function showDocument(ProjetRepository $projetRepository,Repertoire $repertoire,Session $session): Response
    {       

        //$idEq=$equipe->getId();
        //$list=$projetRepository->getRepertoiresByEquipeId(4);
        //$json = json_encode($list);

        $idRep=$repertoire->getId();
        //$listDoc=$projetRepository->getDocumentsByRepertoireId( $idRep);
        $listDoc=$repertoire->getDocument();
         $equipe=$session->get('equipe');
         $utilisateur = $this->getUser();

         //dd($equipe);

        
        $session->set('repertoire',$repertoire);
        //dd($session->get('repertoire'));
       



         
        return $this->render('document/documentList.html.twig',  [
               'documents' =>  $listDoc,'equipe'=>$equipe,'user'=>$utilisateur
        ]);  
    }

    
     
   
    




    /**
     * @Route("/document/newDocument",name="document_new")
     * @param Request $request
     * @return Response
     */

    public function newDocument(Request $request,Session $session): Response
      {
        $document=new Document();
        

        $form=$this->createForm(DocumentType::class,$document);
        $form->handleRequest($request);

        $rep=$session->get('repertoire');
        $package = new Package(new EmptyVersionStrategy());
        
        if($form->isSubmitted() && $form->isValid()){
            
             // On récupère les images transmises
            
            $file=$form->get('file')->getData();
            //dd($file);
            //$session->set('file',$file);
           
            $taille=$file->getSize();
            $type=$file->guessExtension();
            $utilisateur = $this->getUser();
            //$dateCreation=filectime($file);
            $dateCreation= $this->startDate = new \DateTime();
            //dd( $dateCreation);
            $doc = $this->getDoctrine()->getRepository(Repertoire::class);
            $pp=$doc->findOneBy(["id"=>$rep->getId()]);

            $document->setTaille($taille);
            $document->setType($type);
            $document->setAuteur($utilisateur);
            $document->setRepertoire($pp);
            $document->setUrl($file);
            $document->setDateCreation($dateCreation);
            $document->setUrlComplet("");
        
                // On génère un nouveau nom de fichier
                
                $fichier = md5(uniqid()) . '.' . $file->guessExtension();
        // dd($fichier);
                //dd( $package->getUrl('$fichier'));
                // On copie le fichier dans le dossier uploads
                $file->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );

                // On stocke l'image dans la base de données (son nom)
              
                $document->setNom($fichier);
                $document->setFile($file);
                $session->set('doc2',$document);
               
               
           // }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('home');

        }


return $this->render('document/addDocument.html.twig',[
        'form'=>$form->createView() ,'idDoc'=>$document->getId()
    ]);
    }




     /**
     * @Route("/{id}/deleteDocument",name="document_delete")
     * @param Request $request
     */

    public function deleteDocument(Request $request,Session $session,Document $document): Response
      {
                        
                       
                        //$IdAuteur=$document->getAuteur();
                       // $IdUtilisateur=$this->getUser()->getId();
                        //if($IdAuteur==$IdUtilisateur){
                                //dd($auteur->getId());
                               // dd($document);
                        $session->set('document',$document);
                         //dd($session);
                        $entityManager = $this->getDoctrine()->getManager();
                        // $entityManager->persist($document);

                        $entityManager->remove($document);
                        //dd('ok');
                        $entityManager->flush();
                       
                        return $this->redirectToRoute('home');
                                
                        //}
                        //return $this->redirectToRoute('docoment/documentList.html.twig');

                        
      }
 


       /**
     * @Route("/{id}/editDocument",name="document_update")
     * @param Request $request
     */

    public function editDocument(Request $request,Session $session,Document $document): Response
    {
                      
                      //dd($document);
                      
                      //dd('ok');
                     
                     //$file=$document->getFile();
                     // return $this->redirectToRoute('home');
                     $form = $this->createForm(EditDocumentType::class, $document);
                     $form->handleRequest($request);
                     $file=$form->get('file')->getData();
                     $etat=$form->get('Etat')->getData();
                     $version=$form->get('version')->getData();


                     
                       //dd( $document->getFile());
                     if ($form->isSubmitted() && $form->isValid()) {

                        //  $utilisateur = $this->getUser();
                        //  $document->setAuteur(  $utilisateur);
                        //dd($document);
                      
                        $document->setVersion($version);
                        $document->setEtat($etat);
                        $document->setFile($file);
                        
                        
                        //dd($v);
                        
                       
                             $this->getDoctrine()->getManager()->flush();
                            
     
                             return $this->redirectToRoute('home');
                     }
                        
                     return $this->render('document/editDocument.html.twig', [
                     
                     'form' => $form->createView(),
                     ]);
                     

    }

    /**
     * @Route("/{id}/documentDownload", name="document_download")
     */
    public function documentDownload(Document $document)
    {
        // On définit les options du PDF
        $pdfOptions = new Options();
        // Police par défaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);

        // On génère le html
        $html = $this->renderView('document/download.html.twig',['document'=>$document]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère un nom de fichier
        $fichier = 'document-data-'. $document->getId() .'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }




    /*********************  Tag  ******************/
   


/**
     * @Route("/tags/ajout/ajax/{label}",name="tags_add_ajax",methods={"POST"})
     * @return Response
     */

    public function addTagsAjax(string $label,EntityManagerInterface $em,Session $session): Response
    {    //dd($label);
        $session->get('doc');
            $tag= new Tag();
            $tag->setTag(trim(strip_tags($label)));
            //dd($tag);
            // $session->set('document',$document);
        //       $doc=$session->get('document');
        //      $tag->setDocument($doc);
            $em->persist($tag);
            $em->flush();
            $id=$tag->getId();
            return new JsonResponse(['id'=>$id]);

    }


    /**
 * @Route("/tag/newTag",name="tag_new")
 * @param Request $request
 * @return Response
 */

public function newTag(Request $request,Session $session): Response
{
  $tag=new Tag();
  $form=$this->createForm(TagType::class,$tag);
  $form->handleRequest($request);
  $doc=$session->get('document');
  $idDoc=$doc->getId();
        dd($idDoc);
 
  if($form->isSubmitted() && $form->isValid()){
        
        $do = $this->getDoctrine()->getRepository(Document::class);
        $pp=$do->findOneBy(["id"=>$doc->getId()]);
        $tag->setDocument($pp);
          
      $em=$this->getDoctrine()->getManager();
    
      $em->persist($tag);
     
      $em->flush();
                         

      return $this->redirectToRoute('home');
    
      
  }


return $this->render('document/addDocument.html.twig',[
  'form'=>$form->createView(),'idDoc' =>$idDoc
]);}

/*********************** commentaire  ****************/


/**
     * @Route("/{id}/showComment", name="Liste_Comments", methods={"GET"})
     *
     */

    public function showComment(ProjetRepository $projetRepository,Document $document,Session $session): Response
    {       

        $session->set('docu',$document);
        //dd($session->get('docu'));
        $listComments=$document->getCommentaire();
        $list=$projetRepository->getCommentaireByDocumentId($document->getId());
            
              
        return $this->render('commentaire/commentaireList.html.twig',  [
               'comments' =>  $list,
        ]);  
    }


 /**
 * @Route("/newComment",name="comment_new")
 * @param Request $request
 * @return Response
 */

public function newComment(Request $request,Session $session): Response
{
  $comment=new Commentaire();
  $doc3=new Document();
  $form=$this->createForm(CommentaireType::class,$comment);
  $form->handleRequest($request);
  $doc=$session->get('docu'); 
        //dd($doc);
 
  if($form->isSubmitted() && $form->isValid()){
        
        $do = $this->getDoctrine()->getRepository(Document::class);
        $pp=$do->findOneBy(["id"=>$doc->getId()]);
        $comment->setDocument($pp);
          
      $em=$this->getDoctrine()->getManager();
    
      $em->persist($comment);
     
      $em->flush();
                         

      return $this->redirectToRoute('home');
    
      
  }


return $this->render('commentaire/addCommentaire.html.twig',[
  'form'=>$form->createView()
]);



}




/************************ Historique ***********************/



/**
     * @Route("/{id}/showHistorique", name="Liste_Historique", methods={"GET"})
     *
     */

    public function showHistorique(ProjetRepository $projetRepository,Document $document,Session $session, $id): Response
    {       
        
        //$session->set('docum',$document);
        //dd($session->get('docum')->getId());
        //dd($session);
        $id=$document->getId();
        $list=$projetRepository->getHistoriqueByDocumentId($id);
        
        //$doc=$session->get('docu'); 
        //dd($doc);
//     $do = $this->getDoctrine()->getRepository(Document::class);
//    $pp=$do->findOneBy(["id"=>$doc->getId()]);
        //dd($doc);

                
        return $this->render('historique/historiqueList.html.twig',  [
               'historique' =>  $list,'document'=>$document
        ]);  
    }


 /**
 * @Route("/{id}/newEdit",name="edit_new")
 * @param Request $request
 * @return Response
 */

public function newHistorique(Request $request,Session $session,$id): Response
 {
        //dd($session->get('docum')->getId());
        $doc = $this->getDoctrine()->getRepository(Document::class);
       $pp=$doc->findOneBy(["id"=>$id]);
        
        //dd($pp);
  $histo=new Historique();
  $form=$this->createForm(HistoriqueType::class,$histo);
  $form->handleRequest($request);
 
 
  if($form->isSubmitted() && $form->isValid()){
        $ddd=$form->get('document')->getData();

        $file=$form->get('document')->get('file')->getData();

        $v1=$ddd->getVersion();
        $e1=$ddd->getEtat();
        $aut=$this->getUser();
        $remarque=$form->get('remarque')->getData();
       
       $pp->setFile($file);





        $dateModif= $this->startDate = new \DateTime();
        

        $histo->setDateModif($dateModif);
        $histo->setVersionDoc($v1);
        $histo->setDocument($pp);
        $histo->setAut($aut);
        $histo->setEtatDoc($e1);
        $histo->setRemarque($remarque);
        $histo->setFile($file);

   //dd($histo);
          
      $em=$this->getDoctrine()->getManager();
    
      $em->persist($histo);
      //dd('ok');  
      $em->flush();
            

      return $this->redirectToRoute('home');


    
      
  }


return $this->render('historique/addHistorique.html.twig',[
  'form'=>$form->createView()
]);



}




 


    


     

           /****************** users   **************/

     /**
         * @Route("/{id}/listeUsers", name="utilisateur_list", methods={"GET"})
         */
        public function liste(Equipe $equipe): Response
        {
                //accès géré dans le security.yaml
               /* return $this->render('utilisateur/show.html.twig', [
                'utilisateur' => $utilisateur,
                ]);*/
                $list=$equipe->getMembre();
                return $this->render('utilisateur/index.html.twig', [
                        'utilisateurs' => $list,
                ]);
        }

        
        /**
         * @Route("/new", name="utilisateur_new", methods={"GET","POST"})
         */
        public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder, Session $session): Response
        {

                //test de sécurité, un utilisateur connecté ne peut pas s'inscrire
                $utilisateur = $this->getUser();
                // if($utilisateur->getRoles()=='[]')
      if($utilisateur->getRoles()=='[]')

                
                {
                        $session->set("message", "Vous ne pouvez pas créer un compte lorsque vous êtes connecté");
                        return $this->redirectToRoute('membre');
                }

                $utilisateur = new Utilisateur();
                $form = $this->createForm(UtilisateurType::class, $utilisateur);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword()));
                        // uniquement pour créer un admin
                        //$role = ['ROLE_ADMIN'];
                        //$utilisateur->setRoles($role); 
                        $entityManager->persist($utilisateur);
                        $entityManager->flush();

                        return $this->redirectToRoute('utilisateur_index');
                        //return $this->render('navigation/membre.html.twig');
                }

                return $this->render('utilisateur/new.html.twig', [
                'utilisateur' => $utilisateur,
                'form' => $form->createView(),
                ]);
        }

  /**
         * @Route("/newAdmin", name="utilisateur_new_admin", methods={"GET","POST"})
         */
        public function newAdmin(Request $request, UserPasswordEncoderInterface $passwordEncoder, Session $session): Response
        {

                //test de sécurité, un utilisateur connecté ne peut pas s'inscrire
                $utilisateur = $this->getUser();
                if($utilisateur)
                {
                        $session->set("message", "Vous ne pouvez pas créer un compte lorsque vous êtes connecté");
                        return $this->redirectToRoute('membre');
                }

                $utilisateur = new Utilisateur();
                $form = $this->createForm(UtilisateurType::class, $utilisateur);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                        $entityManager = $this->getDoctrine()->getManager();
                        $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword()));
                        // uniquement pour créer un admin
                        $role = ['ROLE_ADMIN'];
                        $utilisateur->setRoles($role); 
                        $entityManager->persist($utilisateur);
                        $entityManager->flush();

                        return $this->redirectToRoute('utilisateur_index');
                }

                return $this->render('utilisateur/new.html.twig', [
                'utilisateur' => $utilisateur,
                'form' => $form->createView(),
                ]);
        }


        /**
         * @Route("/{id}", name="utilisateur_show", methods={"GET"})
         */
        public function show(Utilisateur $utilisateur): Response
        {
                //accès géré dans le security.yaml
                return $this->render('utilisateur/show.html.twig', [
                'utilisateur' => $utilisateur,
                ]);
                
        }

            /**
     * @Route("/showUser", name="Liste_users", methods={"GET"})
     *
     */
//     public function showUsers(ProjetRepository $projetRepository): Response
//     {
        
//         $list=$projetRepository->getUsers();
//         return $this->render('utilisateur/userList.html.twig',  [
//                 'projets' =>$list   ]); 

            
//     }


        

        /**
         * @Route("/{id}/edit2", name="utilisateur_edit2", methods={"GET","POST"})
         */
        public function edit(Request $request, Utilisateur $utilisateur, UserPasswordEncoderInterface $passwordEncoder, Session $session, $id): Response
        {
                $utilisateur = $this->getUser();
                if($utilisateur->getId() != $id )
                {
                        // un utilisateur ne peut pas en modifier un autre
                        $session->set("message", "Vous ne pouvez pas modifier cet utilisateur");
                        return $this->redirectToRoute('membre');
                }
                $form = $this->createForm(UtilisateurType::class, $utilisateur);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                        $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword()));
                        $this->getDoctrine()->getManager()->flush();

                        return $this->redirectToRoute('utilisateur_index');
                }

                return $this->render('utilisateur/edit.html.twig', [
                'utilisateur' => $utilisateur,
                'form' => $form->createView(),
                ]);
        }


        /**
         * @Route("/{id}/edit", name="utilisateur_edit", methods={"GET","POST"})
         */
        public function editAdmin(Request $request, Utilisateur $utilisateur, UserPasswordEncoderInterface $passwordEncoder, Session $session, $id): Response
        {
                $utilisateur = $this->getUser();
                if($utilisateur->getId() != $id )
                {
                        // un utilisateur ne peut pas en modifier un autre
                        $session->set("message", "Vous ne pouvez pas modifier cet utilisateur");
                        return $this->redirectToRoute('admin');
                }
                $form = $this->createForm(UtilisateurType::class, $utilisateur);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                        $utilisateur->setPassword($passwordEncoder->encodePassword($utilisateur, $utilisateur->getPassword()));
                        $this->getDoctrine()->getManager()->flush();

                        return $this->redirectToRoute('utilisateur_index');
                }

                return $this->render('utilisateur/edit.html.twig', [
                'utilisateur' => $utilisateur,
                'form' => $form->createView(),
                ]);
        }

        /**
         * @Route("/{id}", name="utilisateur_delete", methods={"DELETE"})
         */
        public function delete(Request $request, Utilisateur $utilisateur, Session $session, $id): Response
        {
                $utilisateur = $this->getUser();
                if($utilisateur->getId() != $id )
                {
                        // un utilisateur ne peut pas en supprimer un autre
                        $session->set("message", "Vous ne pouvez pas supprimer cet utilisateur");
                        return $this->redirectToRoute('membre');
                }

                if ($this->isCsrfTokenValid('delete'.$utilisateur->getId(), $request->request->get('_token')))
                {
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->remove($utilisateur);
                        $entityManager->flush();
                        // permet de fermer la session utilisateur et d'éviter que l'EntityProvider ne trouve pas la session
                        $session = new Session();
                        $session->invalidate();
                }

                return $this->redirectToRoute('home');
        }


       


      
}