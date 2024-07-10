<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class StationCsvImportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('stationImport', FileType::class, [
                'label' => 'Fichier d\'import des bornes',
                'required' => false,
            ])
        ;
        //Il faudrait bien évidemment sécuriser l'import de fichier,
        // mais il y a du avoir un souci d'encodage avec le fichier csv des bornes.
        //A voir sur le long terme...
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
