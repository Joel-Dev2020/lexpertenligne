<?php

namespace App\Form\Shop;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacturesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('datefacture', TextType::class, [
                'label' => 'Date ou Période',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-1 daterange-1',
                    'placeholder' => 'Sélectionnez une date ou une période',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([

        ]);
    }
}
