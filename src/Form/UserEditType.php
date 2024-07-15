<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Model;
use App\Entity\User;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstname')
            ->add('lastname')
            ->add('address')
            ->add('telNumber')
            ->add('avatar')
            ->add('cars', EntityType::class, [
                'class' => car::class,
                'label' => 'Choissisez le modèle',
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,
                'choice_label' => function (Car $car) {
                    return $car->getModel()->getBrand() . ' ' . $car->getModel()->getModel();
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
