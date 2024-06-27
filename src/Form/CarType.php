<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Model;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('model', EntityType::class, [
                'label' => 'Choissisez le modèle',
                'class' => Model::class,
                'choice_label' => function (Model $model) {
                    return $model->getBrand() . ' ' . $model->getModel();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
