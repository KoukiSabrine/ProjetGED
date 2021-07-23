<?php

namespace App\Form;
namespace App\Form\DataTransformer;


use App\Entity\Document;
use App\Entity\Tag;
use Symfony\Bridge\Doctrine\Form\DataTransformer\CollectionToArrayTransformer;
use Symfony\Component\Form\DataTransformerInterface\TagsTransformer;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('tag')
            //->add('document')
            // ->add('document',EntityType::class,[
            //     'class' => Document::class,
            //     'choice_label' => function ($document) {
            //         return $document->getNom();
            //     }])

            ->addModelTransformer(new CollectionToArrayTransformer(), true)
            //->addModelTransformer(new TagsTransformer($this->manager), true);


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Tag::class,
        ]);
    }
}
