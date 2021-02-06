<?php

namespace App\Form\Formations;

use App\Entity\Formations\Categoriesformations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesformationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom de la catégorie', 'attr' => [
                'placeholder' => 'Nom de la catégorie',
                'class' => 'form-control mb-1'
            ]])
            ->add('ordre', null, ['label' => 'Ordre dans le menu', 'attr' => [
                'placeholder' => 'Ordre dans le menu',
                'class' => 'form-control mb-1',
                'min' => '0',
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categoriesformations::class,
        ]);
    }
}
