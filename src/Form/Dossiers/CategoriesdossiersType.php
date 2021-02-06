<?php

namespace App\Form\Dossiers;

use App\Entity\Dossiers\Categoriesdossiers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesdossiersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom de la catégorie', 'attr' => [
                'placeholder' => 'Nom de la catégorie',
                'class' => ''
            ]])
            ->add('ordre', null, ['label' => 'Numéro d\'ordre', 'attr' => [
                'placeholder' => 'Numéro d\'ordre',
                'class' => '',
                'min' => '0',
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Categoriesdossiers::class,
        ]);
    }
}
