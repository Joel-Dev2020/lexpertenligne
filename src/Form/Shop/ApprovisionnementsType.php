<?php

namespace App\Form\Shop;

use App\Entity\Shop\Approvisionnements;
use App\Entity\Shop\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApprovisionnementsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('products', EntityType::class, [
                'class' => Products::class,
                'label' => 'Produits',
                'required' => false,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez un produit',
                'attr' => [],
            ])
            ->add('newqty', null, ['label' => 'Nouvelle quantité', 'attr' => [
                'placeholder' => 'Nouvelle quantité',
                'min' => 0,
                'class' => 'form-control mb-1'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Approvisionnements::class,
        ]);
    }
}
