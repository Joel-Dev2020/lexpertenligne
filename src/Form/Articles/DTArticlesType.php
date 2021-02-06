<?php

namespace App\Form\Articles;

use App\Entity\Articles\DTArticles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DTArticlesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero_article', null, ['label' => 'Libéllé de l\'article', 'attr' => [
                'placeholder' => 'Libéllé de l\'article'
            ]])
            ->add('keywords', TextareaType::class, ['label' => 'Mots clés (pour le référencement)', 'required' => false, 'attr' => [
                'placeholder' => 'Mots clés (pour le référencement) séparés par un point virgule'
            ]])
            ->add('contenu_article', TextareaType::class, ['label' => 'Contenu de l\'article', 'attr' => [
                'placeholder' => 'Contenu de l\'article',
                'class' => 'ckeditor',
            ]])
            ->add('dtcategories', null, ['label' => 'Sélectionner une catégorie pour l\'article', 'attr' => [
                'placeholder' => 'Sélectionner une catégorie pour l\'article',
                'class' => 'default-select2'
            ]])
            ->add('dtparties', null, ['label' => 'Sélectionner une partie pour l\'article', 'attr' => [
                'placeholder' => 'Sélectionner une partie pour l\'article',
                'class' => 'default-select2'
            ]])
            ->add('dttitres', null, ['label' => 'Sélectionner un titre pour l\'article', 'attr' => [
                'placeholder' => 'Sélectionner un titre pour l\'article',
                'class' => 'default-select2'
            ]])
            ->add('dtchapitres', null, ['label' => 'Sélectionner un chapitre pour l\'article', 'attr' => [
                'placeholder' => 'Sélectionner un chapitre pour l\'article',
                'class' => 'default-select2'
            ]])
            ->add('dtsections', null, ['label' => 'Sélectionner une section pour l\'article', 'attr' => [
                'placeholder' => 'Sélectionner une section pour l\'article',
                'class' => 'default-select2'
            ]])
            ->add('online', null, ['label' => 'Mettre en ligne?'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTArticles::class,
        ]);
    }
}
