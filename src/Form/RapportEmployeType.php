<?php


namespace App\Form;

use App\Entity\RapportEmploye;
use App\Entity\Animal;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RapportEmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
                'choice_label' => 'prenom',
                'placeholder' => 'Choisir un animal',
            ])
            ->add('nourriture', TextType::class, [
                'label' => 'Nourriture',
            ])
            ->add('quantite', TextType::class, [
                'label' => 'Quantité',
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RapportEmploye::class,
        ]);
    }
}
