<?php

namespace App\Form;

use App\Entity\Equipe;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AffectToEquipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomEq',HiddenType::class)
            ->add('projet',HiddenType::class)
            ->add('gerant',HiddenType::class)
             ->add('membre',EntityType::class,[
                'class' => Utilisateur::class,
                'choice_label' => function ($utilisateur) {
                    return $utilisateur->getNom();
                }])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Equipe::class,
        ]);
    }
}
