<?php

namespace App\Form;

use App\Entity\Mind;
use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Department;
use Imagine\Image\Histogram\Hidden;
use Symfony\Component\Form\Button;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class RideFilterType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {

    $user = $options['user'];

    $builder
      ->add('mind', EntityType::class, [
        'class' => Mind::class,
        'attr' => [
          'class' => 'form-control mb-3 border border-dark',
        ],
        'label' => 'Objectif',
        'mapped' => false,
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
      ->add('distance_min', HiddenType::class, [
        'attr' => [
          'class' => 'form-control mb-3 min',
          'min' => 0,
          'max' => 100,
        ],
        'label' => 'Distance (kms)',
        'mapped' => false,
        'data' => '15'
      ])
      ->add('distance_max', HiddenType::class, [
        'attr' => [
          'class' => 'form-control mb-3 max',
          'min' => 0,
          'max' => 100
        ],
        'label' => 'Distance (kms)',
        'mapped' => false,
        'data' => '60'
      ])
      ->add('participants_min', HiddenType::class, [
        'attr' => [
          'class' => 'form-control mb-3 min',
          'min' => 1,
          'max' => 10,
        ],
        'label' => 'Nombre de participants',
        'mapped' => false,
        'data' => '4'
      ])
      ->add('participants_max', HiddenType::class, [
        'attr' => [
          'class' => 'form-control mb-3 max',
          'min' => 1,
          'max' => 10,
        ],
        'label' => 'Nombre de participants',
        'mapped' => false,
        'data' => '8'
      ])
      ->add('ascent_min', HiddenType::class, [
        'attr' => [
          'class' => 'form-control mb-3 min',
          'min' => 0,
          'max' => 2000,
        ],
        'label' => 'Dénivelé (m)',
        'mapped' => false,
        'data' => '200'
      ])
      ->add('ascent_max', HiddenType::class, [
        'attr' => [
          'class' => 'form-control mb-3 max',
          'min' => 0,
          'max' => 2000,
        ],
        'label' => 'Dénivelé (m)',
        'mapped' => false,
        'data' => '1000'
      ])
      ->add('average_speed_min', HiddenType::class, [
        'attr' => [
          'class' => 'form-control mb-3 min',
          'min' => 0,
          'max' => 40,
          'step' => 1
        ],
        'label' => 'Vitesse moyenne (km/h)',
        'mapped' => false,
        'data' => '10'
      ])
      ->add('average_speed_max', HiddenType::class, [
        'attr' => [
          'class' => 'form-control mb-3 max',
          'min' => 0,
          'max' => 40,
          'step' => 1
        ],
        'label' => 'Vitesse moyenne (km/h)',
        'mapped' => false,
        'data' => '30'
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
