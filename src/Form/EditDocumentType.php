<?php

namespace App\Form;

use App\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditDocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',HiddenType::class)
            ->add('url',HiddenType::class)
            ->add('urlComplet',HiddenType::class)
            ->add('Etat')
            ->add('type',HiddenType::class)
            ->add('taille',HiddenType::class)
            //->add('dateCreation',HiddenType::class)
            ->add('version')
            ->add('file', FileType::class, [
                'label' => false,
                //'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
            //->add('repertoire',HiddenType::class)
            //->add('auteur',HiddenType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
