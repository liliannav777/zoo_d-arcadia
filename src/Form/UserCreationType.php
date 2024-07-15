<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options;

class UserCreationFormType extends AbstractType
{
    private $entityManager;

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->entityManager = $options['entityManager'];

        $roles = $this->entityManager->getRepository(Role::class)->findAll();
        $choices = [];
        foreach ($roles as $role) {
            if ($role->getLabel() !== 'ROLE_ADMIN') {
                $choices[$role->getLabel()] = $role;
            }
        }

        $builder
            ->add('username', null, ['label' => 'Nom d\'utilisateur'])
            ->add('nom', null, ['label' => 'Nom'])
            ->add('prenom', null, ['label' => 'Prenom'])
            ->add('password', PasswordType::class, ['label' => 'Mot de Passe'])
            ->add('role', ChoiceType::class, [
                'choices' => $choices,
                'choice_label' => function ($choice) {
                    return $choice instanceof Role ? $choice->getLabel() : '';
                },
                'label' => 'Rôle',
                'placeholder' => 'Choisir un rôle',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
        $resolver->setRequired('entityManager');
    }
}
