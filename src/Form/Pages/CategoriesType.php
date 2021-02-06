<?php

namespace App\Form\Pages;

use App\Entity\Pages\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom de la catégorie',
                'attr' => [
                    'placeholder' => 'Nom de la catégorie',
                    'class' => ''
                ]
            ])
            ->add('ordre', null, ['label' => 'Numéro d\'ordre',
                'attr' => [
                    'placeholder' => 'Numéro d\'ordre',
                    'class' => ''
                ]
            ])
            ->add('parent', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Catégories',
                'placeholder' => 'Aucun parent',
                'required' => false,
                'multiple' => false,
                'group_by' => 'parent',
                'choice_label' => function($categorie){
                    return $categorie->getName();
                },
                'attr' => [
                    'class' => 'select01',
                    'data-toggle' => 'select',
                    'data-live-search' => 'true',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
