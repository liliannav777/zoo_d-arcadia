<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\Race;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('etat', TextType::class, [
                'label' => 'État',
            ])
            ->add('race', EntityType::class, [
                'class' => Race::class,
                'choice_label' => 'label',
                'label' => 'Race',
                'placeholder' => 'Sélectionnez une race',
                'required'=> false
            ])
            ->add('newRace', TextType::class, [
                'label' => 'Nouvelle Race',
                'required' => false,
                'mapped' => false,
            ])
            ->add('imageFile', FileType::class, [
                'label' => 'Image de l\'animal',
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
