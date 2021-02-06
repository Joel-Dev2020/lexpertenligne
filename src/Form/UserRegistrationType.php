<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, [
                'label' => 'Votre nom d\'utilisateur',
                'required' => false,
                'help' => 'Le nom saisi doit comporter au minimum 3 et maximum 15 cataères',
                'attr' => [
                    'placeholder' => 'Votre nom d\'utilisateur',
                    'class' => 'form-control'
                ]
            ])
            ->add('email', null, ['label' => 'Votre adresse email',
                'help' => 'Rassurez vous que l\'email renseigné soit valide', 'attr' => [
                'placeholder' => 'Votre adresse email',
                'class' => 'form-control'
            ]])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne correspondent pas.',
                'options' => ['attr' => ['class' => 'form-control']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe', 'help' => 'Le mot de passe doit comporter au minimum 6 cataères', 'attr' => ['placeholder' => 'Mot de passe']],
                'second_options' => ['label' => 'Confirmer le mot de passe', 'help' => 'Confirmation du mot de passe (minimum 6 cataères)', 'attr' => ['placeholder' => 'Confirmer le mot de passe']],
            ])
            ->add('terms', CheckboxType::class, ['label' => false, 'attr' => ['class' => '']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
