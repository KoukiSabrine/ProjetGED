<?php

// src/DataPersister

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Document;
use App\Entity\Projet;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File as FileObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;





class UserDataPersister implements ContextAwareDataPersisterInterface

{
    private $em;
    public function __construct(EntityManagerInterface $entityManager)
       
     {
        $this->em = $entityManager;
       
    }
    public function supports($data, array $context = []): bool
    {
        // return $data instanceof Utilisateur;
        // return $data instanceof Projet;
        return $data instanceof Document;

    }

    public function __invoke(Request $request): Document
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $document = new Document();
        $document->file = $uploadedFile;

        return $document;
    }

   
    // public getProjetByUser($id): Projet{

    // }


    /**
     * @param Document $data
    
     */
    public function persist($data, array $context = [])
    {
        
        if ($data instanceof Document){
        // $data->setFile("f2");
        $data->setDateCreation(new \DateTime());
        // $data->setFile( new FileObject("C:\\wamp64\\tmp\\phpF57D.tmp"));
        $this->em->persist($data);
        $this->em->flush();
        }
        // else if($data instanceof Projet){

        // }
    }

    public function remove($data, array $context = [])
    {
        $this->em->remove($data);
        $this->em->flush();
    }

    
}