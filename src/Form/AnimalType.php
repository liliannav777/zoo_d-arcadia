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

class AnimalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
            ])
            ->add('etat', ChoiceType::class, [
                'choices' => [
                    'En bonne santé' => 'En bonne santé',
                    'Malade' => 'Malade',
                ],
                'label' => 'État',
            ])
            ->add('race', EntityType::class, [
                'class' => Race::class,
                'choice_label' => 'label',
                'label' => 'Race',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Animal::class,
        ]);
    }
}
