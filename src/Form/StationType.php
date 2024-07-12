<?php

namespace App\Form;

use App\Entity\Stations;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('idStation')
            ->add('stationName')
            ->add('stationAddress')
            ->add('inseeCode')
            ->add('longitude')
            ->add('latitude')
            ->add('maxPower')
            ->add('isFree')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stations::class,
        ]);
    }
}
