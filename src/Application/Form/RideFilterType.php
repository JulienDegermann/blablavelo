<?php

namespace App\Application\Form;

use App\Domain\Location\Department;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use App\Domain\Ride\UseCase\FindRides\FindRidesInput;
use App\Domain\User\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RideFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
            ->add('practice', EntityType::class, [
                'class' => Practice::class,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
                'label' => 'Pratique',
                'data' => $user->getPractice(),
            ])
            ->add('mind', EntityType::class, [
                'class' => Mind::class,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
                'label' => 'Objectif',
                'data' => $user->getMind(),
            ])
            ->add('department', EntityType::class, [
                'class' => Department::class,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
                'data' => $user->getDepartment(),
                'label' => 'Département',
            ])
            ->add('startDate', DateTimeType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
                'widget' => 'single_text',
                'label' => 'Date',
                'required' => false,
            ])
            ->add('minDistance', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 min',
                    'min' => 0,
                    'max' => 100,
                ],
                'label' => 'Distance (kms)',
                'data' => '15',
            ])
            ->add('maxDistance', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 max',
                    'min' => 0,
                    'max' => 100,
                ],
                'label' => 'Distance (kms)',
                'data' => '60',
            ])
            ->add('minParticipants', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 min',
                    'min' => 1,
                    'max' => 10,
                ],
                'label' => 'Nombre de participants',
                'data' => '4',
            ])
            ->add('maxParticipants', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 max',
                    'min' => 1,
                    'max' => 10,
                ],
                'label' => 'Nombre de participants',
                'data' => '8',
            ])
            ->add('minAscent', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 min',
                    'min' => 0,
                    'max' => 2000,
                ],
                'label' => 'Dénivelé (m)',
                'data' => '200',
            ])
            ->add('maxAscent', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 max',
                    'min' => 0,
                    'max' => 2000,
                ],
                'label' => 'Dénivelé (m)',
                'data' => '1000',
            ])
            ->add('minAverageSpeed', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 min',
                    'min' => 0,
                    'max' => 40,
                    'step' => 1,
                ],
                'label' => 'Vitesse moyenne (km/h)',
                'data' => '10',
            ])
            ->add('maxAverageSpeed', HiddenType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 max',
                    'min' => 0,
                    'max' => 40,
                    'step' => 1,
                ],
                'label' => 'Vitesse moyenne (km/h)',
                'data' => '30',
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mb-3 text-white px-4 py-2',
                ],
                'label' => 'Appliquer les filtres',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults(['data_class' => FindRidesInput::class, 'user' => User::class])
            ->setAllowedTypes('user', [User::class]);
    }
}
