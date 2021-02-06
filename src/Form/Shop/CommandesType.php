<?php

namespace App\Form\Shop;

use App\Entity\Shop\Commandes;
use App\Entity\Status;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'help' => 'Sélectionner un état de commande',
                'label' => 'Status',
                'required' => false,
                'choice_label' => 'name',
                'attr' => [],
            ])
            ->add('motifs', TextareaType::class, ['label' => 'Motif de l\'action selectionnée',
                'help' => 'Donner le motif du status sélectionné (Facultatif)',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Donnez un motif du status ici...',
                    'class' => 'form-control  mb-1'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commandes::class,
        ]);
    }
}
