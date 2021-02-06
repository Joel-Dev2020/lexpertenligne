<?php

namespace App\Form\Baremes;

use App\Entity\Baremes\Secteurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SecteursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom du secteur d\'activité', 'attr' => [
                'placeholder' => 'Nom du secteur d\'activité',
                'class' => ''
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Secteurs::class,
        ]);
    }
}
