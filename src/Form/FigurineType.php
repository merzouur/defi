<?php

namespace App\Form;

// src/Form/FigurineType.php
namespace App\Form;

use App\Entity\Figurine;
use App\Entity\Oeuvre; // Assurez-vous d'importer l'entité Oeuvre
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FigurineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('oeuvre', EntityType::class, [
                'class' => Oeuvre::class, // Spécifiez l'entité Oeuvre
                'choice_label' => 'name', // Remplacez 'name' par le champ que vous souhaitez afficher dans le menu déroulant
                'placeholder' => 'Choisissez une œuvre',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Figurine::class,
        ]);
    }
}

