<?php

namespace App\Form\Articles;

use App\Entity\Articles\Categories;
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
                'label' => 'Parents',
                'placeholder' => 'Aucun parent',
                'required' => false,
                'group_by' => 'parent',
                'choice_label' => function($categorie){
                    return $categorie->getName();
                },
                'attr' => [
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
