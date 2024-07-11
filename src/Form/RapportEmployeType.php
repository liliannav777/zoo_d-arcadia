<?php
// src/Form/RapportEmployeType.php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\RapportEmploye;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RapportEmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'prenom',
                'label' => 'Animal',
                'placeholder' => 'Choisissez un animal',
                'required' => true,
            ])
            ->add('nourriture', TextType::class, [
                'label' => 'Nourriture',
            ])
            ->add('quantite', TextType::class, [
                'label' => 'Quantité (kg)',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'input' => 'datetime_immutable',
                'data' => new \DateTimeImmutable('now'),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RapportEmploye::class,
        ]);
    }
}