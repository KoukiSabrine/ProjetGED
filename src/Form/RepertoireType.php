<?php

namespace App\Form;

use App\Entity\Repertoire;
use App\Entity\Equipe;



use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class RepertoireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            //->add('pId',HiddenType::class)
            //->add('open',HiddenType::class)
            //->add('equipe',HiddenType::class)
           /* ->add('equipe',EntityType::class,[
                'class' => Equipe::class,
                'choice_label' => function ($equipe) {
                    return $equipe->getNom();
                }
              
            ])*/
            //->add('equipe',HiddenType::class)
            //->add('repertoire',HiddenType::class)
            ->add('repertoire',EntityType::class,[
                'class' => Repertoire::class,
                'choice_label' => function ($repertoire) {
                    return $repertoire->getNom();
                }
              
            ]) 

           // ->add('repertoire',HiddenType::class)
            //->add('sousRepertoire',HiddenType::class)
           
            
        ;
    } 

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Repertoire::class,
        ]);
    }
}
