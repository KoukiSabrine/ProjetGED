<?php
// api/src/Controller/CreateMediaObjectAction.php

namespace App\Controller;

use App\Entity\Document;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

#[AsController]
final class DocumentController extends AbstractController
{
    public function __invoke(Document $document,Request $request)
    {   
        $document=$request->attributes->get('data');
        if(!($document instanceOf Document)){
            throw new \RuntimeException('document attendu');
        }
        $file = $request->files->get('file');
        $document->setFile($request->files->get('file'));
        $document->setDateCreation(new \DateTime());
        $document->setUrl("u1");
        $document->setUrlComplet("uc1");
        $document->setEtat("début");
        $document->setType("png");
        $document->setTaille("26");
        $document->setVersion("2.0");

        return $document;
       
    }
}