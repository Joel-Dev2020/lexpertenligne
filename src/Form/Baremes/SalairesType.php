<?php

namespace App\Form\Baremes;

use App\Entity\Baremes\Categories;
use App\Entity\Baremes\Salaires;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalairesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('salairehoraire', null, ['label' => 'Salaire horaire', 'attr' => [
                'placeholder' => 'Salaire horaire',
                'class' => ''
            ]])
            ->add('salairemensuel', null, ['label' => 'Salaire mensuel', 'attr' => [
                'placeholder' => 'Salaire mensuel',
                'class' => ''
            ]])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Catégories',
                'placeholder' => 'Sélectionner une catégorie',
                'required' => false,
                'choice_label' => 'name',
                'attr' => [
                    'data-live-search' => 'true',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Salaires::class,
        ]);
    }
}
