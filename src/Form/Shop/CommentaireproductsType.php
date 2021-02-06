<?php

namespace App\Form\Shop;

use App\Entity\Shop\Commentaireproducts;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentaireproductsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('message', TextareaType::class, [
                'attr' => [
                    'rows' => '8',
                    'placeholder' => 'Saisissez votre avi ici...',
                ],
                'label' => 'Votre message (1500 caractères maximum)',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaireproducts::class,
        ]);
    }
}
