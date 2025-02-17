<?php

// src/Form/OeuvreType.php
namespace App\Form;

use App\Entity\Oeuvre;
use App\Entity\Category; // Assurez-vous d'importer l'entité Category
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OeuvreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('category', EntityType::class, [
                'class' => Category::class, // Spécifiez l'entité Category
                'choice_label' => 'name', // Remplacez 'name' par le champ que vous souhaitez afficher dans le menu déroulant
                'placeholder' => 'Choisissez une catégorie',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Oeuvre::class,
        ]);
    }
}

