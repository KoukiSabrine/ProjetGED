<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('projet',HiddenType::class)
            ->add('gerant',EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => function ($utilisateur) {
                    return $utilisateur->getPrenom();
                }])
            //->add('membre',CheckboxType::class)
            //->add('membre',ChoiceType::class)

            ->add('membre',EntityType::class,[
                'class' => Utilisateur::class,
                
                'multiple'=>true,
                //'expanded'=>true,
                'query_builder'=>function(EntityRepository $er){
                    return $er->createQueryBuilder('t')
                    ->orderBy('t.nom','ASC');

                },
                'label'=>'Membre',
                'by_reference'=>false,
                'attr'=>[
                    'class'=>'select-tags'
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
