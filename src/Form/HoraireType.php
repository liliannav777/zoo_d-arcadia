<?php
// src/Form/HoraireType.php

namespace App\Form;

use App\Entity\Horaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HoraireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Entrez une description des horaires','rows' => 3],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Horaire::class,
        ]);
    }
}
