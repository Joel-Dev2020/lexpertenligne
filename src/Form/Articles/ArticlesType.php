<?php

namespace App\Form\Articles;

use App\Entity\Articles\Categories;
use App\Entity\Articles\Articles;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom de la page', 'attr' => [
                'placeholder' => 'Nom de la page',
                'class' => 'form-control-lg'
            ]])
            ->add('content', CKEditorType::class, [
                'label' => 'Contenu',
                'attr' => [
                    'class' => 'form-control-lg',
                    'placeholder' => 'Contenu de la page'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'label' => 'Catégories',
                'placeholder' => 'Aucune catégorie',
                'required' => true,
                'multiple' => true,
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
            'data_class' => Articles::class,
        ]);
    }
}
