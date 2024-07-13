<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\RapportVeterinaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class RapportVeterinaireType extends AbstractType
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
                'attr' => ['class' => 'form-control'],
            ])
            ->add('etatAnimal', TextType::class, [
                'label' => 'État de l’Animal',
                'required' => true,
            ])
            ->add('nourriture', TextType::class, [
                'label' => 'Nourriture Proposée',
                'required' => true,
            ])
            ->add('grammage', TextType::class, [
                'label' => 'Grammage de la Nourriture (en kg)',
                'required' => true,
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de Passage',
                'required' => true,
            ])
            ->add('detailEtatAnimal', TextareaType::class, [
                'label' => 'Détail de l’État de l’Animal',
                'required' => false,
            ])
            ->add('commentaireHabitat', TextareaType::class, [
                'label' => 'Commentaire sur l\'Habitat',
                'required' => false,
                'empty_data' => '',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RapportVeterinaire::class,
        ]);
    }
}
