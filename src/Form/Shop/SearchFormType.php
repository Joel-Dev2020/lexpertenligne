<?php

namespace App\Form\Shop;

use App\Data\SearchData;
use App\Entity\Shop\Categories;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Trouvez un ou plusieurs produits...',
                ],
            ])
            ->add('categories', EntityType::class, [
                'required' => false,
                'label' => false,
                'class' => Categories::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('min', IntegerType::class, [
                'required' => false,
                'label' => false,
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Prix min',
                    'min' => '1',
                ],
            ])
            ->add('max', IntegerType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Prix max',
                    'min' => '1',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
