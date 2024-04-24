<?php

namespace App\Form;

use App\Entity\Mind;
use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Department;
use Imagine\Image\Histogram\Range;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class RideFilterType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {

    $user = $options['user'];

    $builder
      ->add('mind', EntityType::class, [
        'class' => Mind::class,
        'attr' => [
          'class' => 'form-control mb-3 border border-dark'
        ],
        'label' => 'Objectif',
        'mapped' => false
      ])
      ->add('department', EntityType::class, [
        'class' => Department::class,
        'attr' => [
          'class' => 'form-control mb-3 border border-dark',
        ],
        'data' => $user->getDepartment(),
        'label' => 'Département',
        'mapped' => false
      ])
      ->add('date', DateTimeType::class, [
        'attr' => [
          'class' => 'form-control mb-3 border border-dark',          
        ],
        'widget' => 'single_text',
        'label' => 'Date',
        'mapped' => false,
        'required' => false
      ])
      ->add('distance', RangeType::class, [
        'attr' => [
          'class' => 'form-control mb-3',
          'min' => 0,
          'max' => 300,
          'step' => 5
        ],
        'label' => 'Distance (kms)',
        'mapped' => false
      ])
      ->add('participants', RangeType::class, [
        'attr' => [
          'class' => 'form-control mb-3',
          'min' => 1,
          'max' => 10,
          'step' => 1
        ],
        'label' => 'Nombre de participants',
        'mapped' => false
      ])
      ->add('ascent', RangeType::class, [
        'attr' => [
          'class' => 'form-control mb-3',
          'min' => 0,
          'max' => 3000,
          'step' => 50
        ],
        'label' => 'Dénivelé (m)',
        'mapped' => false
      ])
      ->add('average_speed', RangeType::class, [
        'attr' => [
          'class' => 'form-control mb-3',
          'min' => 0,
          'max' => 50,
          'step' => 1
        ],
        'label' => 'Vitesse moyenne (km/h)',
        'mapped' => false
      ])
      ->add('submit', SubmitType::class, [
        'attr' => [
          'class' => 'btn btn-primary mb-3 text-white px-4 py-2'
        ],
        'label' => 'Appliquer les filtres'
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver
      ->setDefaults(['data_class' => null, 'user' => null])
      ->setAllowedTypes('user', [User::class, null]);
  }
}
