<?php

namespace App\Form;

use App\Entity\Document;
use App\Entity\Utilisateur;
use App\Entity\Repertoire;
use App\Entity\Tag;
use App\Form\DataTransformer\TagTransformer;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;


class DocumentType extends AbstractType
{
    public function __construct(TagTransformer $transformer)
    {
        $this->transformer = $transformer;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',HiddenType::class)
            //->add('url')
            //->add('urlComplet')
            ->add('Etat')
           /* ->add('auteur',EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => function ($auteur) {
                    return $auteur->getPrenom();
                }])*/
            ->add('auteur',HiddenType::class)    
            ->add('type',HiddenType::class)
            ->add('taille',HiddenType::class)
            ->add('tag',HiddenType::class)
            ->add('tag',EntityType::class,[
                'class' => Tag::class,
                'choice_label' => function ($tag) {
                    return $tag->getTag();
                },
                'multiple'=>true,
                //'expanded'=>true,
                'query_builder'=>function(EntityRepository $er){
                    return $er->createQueryBuilder('t')
                    ->orderBy('t.tag','ASC');

                },
                'label'=>'Mots-clés',
                'by_reference'=>false,
                'attr'=>[
                    'class'=>'select-tags'
                ]
                ])


        //     ->add('tag', ChoiceType::class,
        //     [
        //        'choices'  =>
        //        [  
        //            'Apprenti' => 'Apprenti',
        //            'CDI' => 'CDI',
        //            'Contrat Pro' => 'Contrat_Pro',
        //            'Contrat Pro UDEV' => 'Contrat_Pro_UDEV',
        //            'Stagiaire' => 'Stagiaire',
        //            'Tous' => 'Tous', //Ajout d’un champ ‘Tous’
                    
 
        //        ],
        //        'multiple'=>true,
        //        'expanded'=>true,
      
                
        //    ])


            //->add('tag', CollectionType::class)
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
        /*$builder->get('tag')
            ->addModelTransformer(new CallbackTransformer(
                //transform
                function ($tagsAsArray) {
                    // transform the array to a string
                    if (isset($tagsAsArray[1])) {
                    return implode(', ', $tagsAsArray);}
                    return '';
                },
                //reverseTransform (ce que s'enregistre dans la DB)
                function ($tagsAsString) {
                    // transform the string back to an array
                    return explode(', ', $tagsAsString);
                }
            ));*/
            // $builder->get('tag')
            // ->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
