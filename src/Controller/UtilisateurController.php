<?php


namespace App\Controller;

use App\Entity\Document;
use App\Entity\Equipe;
use App\Entity\File;
use App\Entity\Utilisateur;
use App\Entity\Projet;
use App\Entity\Repertoire;
use App\Entity\Tag;
use App\Form\RepertoireType;
use App\Form\DocumentType;
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

        private $directoryList;
         private $s3;
        /*public function __construct(ApiService $apiService){

        }*/
        /**
        * @Route("/", name="utilisateur_index", methods={"GET"})
        */
        public function index(UtilisateurRepository $utilisateurRepository, Session $session): Response
        {
                //besoin de droits admin
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
                        return $this->render('navigation/admin.html.twig');
                }

                return $this->redirectToRoute('membre');
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
                'projets' =>$list]); 

            
    }


    
         /*****************     Equipe      ********************/


          /**
     * @Route("/{id}/showEquipe", name="Liste_Equipes", methods={"GET"})
     *
     */
       public function showEquipe(Projet $projet,Session $session): Response
    {       
        $list = $projet->getEquipe();
        
       // $session->set('list',$list);
        //dd($session->get('list'));
        $utilisateur = $this->getUser();
        return $this->render('equipe/equipeList.html.twig',  [
                'equipes' =>  $list,
                'u' => $utilisateur
        ]);  
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

    public function showRepertoire(ProjetRepository $projetRepository,Equipe $equipe,Repertoire $repertoire,Session $session): Response
    {       
        //$utilisateur = $this->getUser();
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
    
     $listSousRep=$repertoire->getRepertoire();
      
     $idRep=$repertoire->getId();
     $listDoc=$projetRepository->getDocumentsByRepertoireId($idRep);
   
        return $this->render('repertoire/repertoireList.html.twig',  [
                'repertoires' =>  $list,'zNodes' => $json,'documents' => $listDoc
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
        
     
     $listSousRep=$repertoire->getSousRepertoire();
    /* $ss=$session->get('sr');
     dd($ss);

     $rep = $this->getDoctrine()->getRepository(Repertoire::class);
     $pp=$rep->findOneBy(["id"=>$ss->getId()]);
     dd($pp);
     $listSousRep->addSousRepertoire($pp);*/
     


   
        return $this->render('sousRepertoire/sousRepertoireList.html.twig',  [
             'sousRepertoires'=> $listSousRep
        ]);  
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
        $listDoc=$projetRepository->getDocumentsByRepertoireId( $idRep);

        
        $session->set('repertoire',$repertoire);
        //dd($repertoire);
       



         
        return $this->render('document/documentList.html.twig',  [
               'documents' =>  $listDoc,
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
        

        //dd($rep);
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

           

            //$dobStringValue = $dateCreation->format('Y-m-d');
            //$date = \DateTime::createFromFormat('Y-m-d', $dateCreation); 
            // dd($date);
            $document->setTaille($taille);
            $document->setType($type);
            $document->setAuteur($utilisateur);
            $document->setRepertoire($pp);
            $document->setUrl($file);
            $document->setDateCreation($dateCreation);
            //$document->setDateCreation($dateCreation);
        
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
               // $f = new File();
                $document->setNom($fichier);
                $document->setFile($file);
           // }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('home');

        }


return $this->render('document/addDocument.html.twig',[
        'form'=>$form->createView()
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
                     $form = $this->createForm(DocumentType::class, $document);
                     $form->handleRequest($request);
                     
                     
                     //dd($form);
                     //dd($form->getData());
                     //dd('ppp');
                     
                       //dd( $document);
                     if ($form->isSubmitted() && $form->isValid()) {

                         $utilisateur = $this->getUser();
                         $document->setAuteur(  $utilisateur);
                        
                        $document->setVersion('111');
                        
                        
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
        //dd($doc);
 
  if($form->isSubmitted() && $form->isValid()){
        
        $do = $this->getDoctrine()->getRepository(Document::class);
        $pp=$do->findOneBy(["id"=>$doc->getId()]);
        $tag->setDocument($pp);
          
      $em=$this->getDoctrine()->getManager();
    
      $em->persist($tag);
     
      $em->flush();
                         

      return $this->redirectToRoute('home');
    
      
  }


return $this->render('tag/addTag.html.twig',[
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