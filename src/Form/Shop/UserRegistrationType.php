<?php

namespace App\Form\Shop;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, ['label' => 'Identifiant', 'attr' => [
                'placeholder' => 'Votre identifiant',
                'class' => 'mb-1',
                'style' => 'width:100%!important;'
            ]])
            ->add('nom', null, ['label' => 'Nom', 'required' => false, 'help' => 'Le nom saisi doit comporter au minimum 3 et maximum 15 cataères', 'attr' => [
                'placeholder' => 'Nom',
                'class' => 'mb-1',
                'style' => 'width:100%!important;'
            ]])
            ->add('prenoms', null, ['label' => 'Prenoms', 'required' => false, 'help' => 'Le prenom saisi doit comporter au minimum 3 et maximum 100 cataères', 'attr' => [
                'placeholder' => 'Prenoms',
                'class' => 'mb-1',
                'style' => 'width:100%!important;'
            ]])
            ->add('email', null, ['label' => 'Email de l\'utilisateur',
                'help' => 'Rassurez vous que l\'email renseigné soit valide', 'attr' => [
                'placeholder' => 'Adresse email',
                'class' => 'mb-1 text-mask-email',
                    'style' => 'width:100%!important;'
            ]])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passes ne correspondent pas.',
                'help' => 'Le mot de passe doit comporter au minimum 6 cataères',
                'options' => ['attr' => ['class' => '']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe', 'attr' => ['placeholder' => 'Mot de passe', 'style' => 'width:100%!important;']],
                'second_options' => ['label' => 'Confirmer le mot de passe', 'attr' => ['placeholder' => 'Confirmer le mot de passe', 'style' => 'width:100%!important;']],
            ])
            ->add('contacts', null, ['label' => 'Numéro de téléphone', 'required' => false, 'attr' => [
                'placeholder' => 'Numéro de téléphone',
                'class' => 'form-control mb-1',
                'style' => 'width:100%!important;'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    private function getChoices(){
        $choices = User::CIVILITE;
        $output = [];
        foreach ($choices as $k => $v){
            $output[$v] = $k;
        }
        return $output;
    }

    private function getRaisonsociale(){
        $choices = User::RAISON;
        $output = [];
        foreach ($choices as $k => $v){
            $output[$v] = $k;
        }
        return $output;
    }
}
