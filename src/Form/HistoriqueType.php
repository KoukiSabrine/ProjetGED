<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Historique;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoriqueType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateModif',HiddenType::class)
            //->add('document',HiddenType::class)
            ->add('document',EditDocumentType::class)
            ->add('remarque')

            // ->add('document',EntityType::class,[
            //     'class' => Document::class,
            //     'choice_label' => function ($document) {
            //         return $document->getNom();
            //     }])
        //    ->add('dateModif', DateType::class, [
        //         'widget' => 'choice',
        //         'input'  => 'datetime',
        //         'format' => 'yyyy-MM-dd'
        //     ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Historique::class,
        ]);
    }
}
