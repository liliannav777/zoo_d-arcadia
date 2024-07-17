<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                $choices[$role->getLabel()] = $role->getLabel();
            }
        }

        $builder
            ->add('username', null, ['label' => 'Nom d\'utilisateur'])
            ->add('nom', null, ['label' => 'Nom'])
            ->add('prenom', null, ['label' => 'Prénom'])
            ->add('password', PasswordType::class, ['label' => 'Mot de Passe'])
            ->add('role', EntityType::class, [
                'class' => Role::class,
                'choice_label' => 'label',  // Affiche le label du rôle
                'placeholder' => 'Choisir un rôle',
                'label' => 'Rôle',
                'query_builder' => function($repo) {
                    return $repo->createQueryBuilder('r')
                        ->where('r.label IN (:roles)')
                        ->setParameter('roles', ['ROLE_EMPLOYE', 'ROLE_VETERINAIRE']);
                },
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
