<?php

namespace App\Form\Shop;

use App\Entity\Shop\Metakeywords;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MetakeywordsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Mots clés', 'attr' => [
                'placeholder' => 'Mots clés',
                'class' => 'form-control mb-1'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Metakeywords::class,
        ]);
    }
}
