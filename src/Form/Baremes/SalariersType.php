<?php

namespace App\Form\Baremes;

use App\Entity\Baremes\Salariers;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SalariersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Libellé', 'attr' => [
                'placeholder' => 'Libellé',
                'class' => ''
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Salariers::class,
        ]);
    }
}
