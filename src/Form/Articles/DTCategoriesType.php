<?php

namespace App\Form\Articles;

use App\Entity\Articles\DTCategories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DTCategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Ajouter une catégorie', 'attr' => [
                'placeholder' => 'Ajouter une catégorie'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DTCategories::class,
        ]);
    }
}
