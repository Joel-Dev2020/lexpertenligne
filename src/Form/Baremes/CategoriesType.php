<?php

namespace App\Form\Baremes;

use App\Entity\Baremes\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Libellé', 'attr' => [
                'placeholder' => 'Libellé',
                'class' => ''
            ]])
            ->add('definition', null, [
                'label' => 'Définition',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Définition',
                    'class' => ''
                ]
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
