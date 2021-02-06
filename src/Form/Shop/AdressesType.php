<?php

namespace App\Form\Shop;

use App\Entity\Shop\Adresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomprenoms', TextType::class, ['label' => 'Nom & prénoms', 'attr' => [
                'placeholder' => 'Nom & prénoms',
            ]])
            ->add('contacts', TextType::class, ['label' => 'Numéro de téléphone', 'attr' => [
                'placeholder' => 'Numéro de téléphone',
            ]])
            ->add('email', EmailType::class, ['label' => 'Adresse email', 'attr' => [
                'placeholder' => 'Exp: info@monmail.com',
            ]])
            ->add('adresse', TextareaType::class, ['label' => 'Nouvelle adresse', 'attr' => [
                'placeholder' => 'Votre adresse ici...',
                'class' => 'form-control'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Adresses::class,
        ]);
    }
}
