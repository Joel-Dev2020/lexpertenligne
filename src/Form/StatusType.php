<?php

namespace App\Form;

use App\Entity\Status;
use App\Form\Types\ChoiceIconType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['label' => 'Nom', 'attr' => [
                'placeholder' => 'Nom du status',
                'class' => 'form-control mb-1'
            ]])
            ->add('color', null, ['label' => 'Couleur', 'attr' => [
                'placeholder' => 'Couleur du status',
                'autocomplete' => 'off',
                'class' => 'form-control mb-1 minicolors-saturation'
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Status::class,
        ]);
    }
}
