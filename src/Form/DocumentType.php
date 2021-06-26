<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Utilisateur;
use App\Entity\Repertoire;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;


class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',HiddenType::class)
            //->add('url')
            ->add('urlComplet')
            ->add('Etat')
           /* ->add('auteur',EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => function ($auteur) {
                    return $auteur->getPrenom();
                }])*/
            ->add('auteur',HiddenType::class)    
            ->add('type',HiddenType::class)
            ->add('taille',HiddenType::class)
            ->add('tag')
            //->add('url',HiddenType::class)
            /*->add('dateCreation', DateType::class, [
                'widget' => 'choice',
                'input'  => 'datetime',
                'format' => 'yyyy-MM-dd'
            ])*/
            
            //->add('dateCreation',HiddenType::class)
            ->add('version')
           /* ->add('repertoire',EntityType::class,[
                'class' => Repertoire::class,
                'choice_label' => function ($repertoire) {
                    return $repertoire->getNom();
                }])*/
            /*->add('repertoire',EntityType::class,[
                'class' => Repertoire::class,
                'choice_label' => function ($repertoire) {
                    return $repertoire->getNom();
                }]);*/
            /*->add('file', FileType::class, array(
                'label' 	=> false,
                'required' 	=> true,
                'constraints' => array(
                    new File(),
                ),
            ));*/
            ->add('file', FileType::class, [
                'label' => false,
                //'multiple' => true,
                'mapped' => false,
                'required' => false
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
