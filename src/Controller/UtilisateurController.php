<?php


namespace App\Controller;

use App\Entity\Equipe;
use App\Entity\Utilisateur;
use App\Entity\Projet;
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

/**
* @Route("/utilisateur")
*/
class UtilisateurController extends AbstractController
{
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
        //$projet=

         //$utilisateur=$this->getUser();
           /* return $this->render('projet/projetList.html.twig',  [
                'equipes' => $utilisateur->getEquipes()
        ]); */

        $list=$projetRepository->getProjetsByUserId($id);
        return $this->render('projet/projetList.html.twig',  [
                'projets' =>$list]); 

            
    }


    
         /*****************     Equipe      ********************/

          /**
     * @Route("/{id}/showEquipe", name="Liste_Equipes", methods={"GET"})
     *
     */
       public function showEquipe(Projet $projet): Response
    {       
        //$id = $this->getUser()->getId();
        //$id2=$projetRepository->getProjetIdByUserId($id);
        //$projet=new Projet();
        //$id = $projet->getId();
        //$list2=$projetRepository->getEquipesByProjetId($id);
        $list = $projet->getEquipe();
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



     /*****************     User      ********************/

          /**
     * @Route("/{id}/showRepertoire", name="Liste_Repertoires", methods={"GET"})
     *
     */

    public function showRepertoire(ProjetRepository $projetRepository,Equipe $equipe): Response
    {       
        //$utilisateur = $this->getUser();
        $idEq=$equipe->getId();
     $list=$projetRepository->getRepertoiresByEquipeId($idEq);
     //$list=$
        return $this->render('repertoire2/repertoireList.html.twig',  [
                'repertoires' =>  $list
        ]);  
    }



     

    /*********** users   *******/

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