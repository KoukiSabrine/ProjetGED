<?php
// src/Form/DataTransformer/TagTransformer.php
namespace App\Form\DataTransformer;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TagTransformer implements DataTransformerInterface
{
    private $entityManager;
    //private $tagRepository;

    public function __construct(EntityManagerInterface $entityManager,TagRepository $tagRepository)
    {
        $this->entityManager = $entityManager;
        //$this->$tagRepository=$tagRepository;
        
    }

    
    /**
     * Transforms an object (tag) to a string (number).
     *
     * @param  Tag|null  $tag
     */
    public function transform($tag): string
    {
        if (null === $tag) { 
            return '';
        }
     //dd($tag);

     //$doc = $this->tagRepository->getRepository(Tag::class);
     $pp=$this->tagRepository->findOneBy(["id"=>$tag->getId()]);
     //dd($pp);

    
        // transform the array to a string
       
        //$t= implode(', ', $tag);
       


        dd($pp);
        return $tag->getId();
        //return 'yep';
}

    /**
     * Transforms a string (number) to an object (tag).
     *
     * @param  string $tagNumber
     * @throws TransformationFailedException if object (tag) is not found.
     */
    public function reverseTransform($tagNumber): ?Tag
    {
        // no tag number? It's optional, so that's ok
        if (!$tagNumber) {
            return null;
        }

        $tag = $this->entityManager
            ->getRepository(Tag::class)
            // query for the tag with this id
            ->find($tagNumber)
        ;

        if (null === $tag) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'A tag with number "%s" does not exist!',
                $tagNumber
            ));
        }
        //dd($tag);
        return $tag;
    }

    
}
