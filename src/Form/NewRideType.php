<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Mind;
use App\Entity\Ride;
use DateTimeImmutable;
use App\Entity\Practice;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Doctrine\DBAL\Types\DateTimeImmutableType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTyoe;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


class NewRideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $user = $options['user'];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de la sortie',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3',
            ]])
            ->add('distance', IntegerType::class, [
                'label' => 'Distance estimée (kms)',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('ascent', IntegerType::class, [
                'label' => 'Dénivelé positif estimé',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3',
                ]
            ])
            ->add('max_rider', IntegerType::class, [
                'label' => 'Nombre maximum de participants',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'constraints' => [
                    new Assert\Range([
                        'min' => 1,
                        'max' => 10,
                        'minMessage' => 'Le nombre de participants doit être compris entre 1 et 10.',
                        'maxMessage' => 'Le nombre de participants doit être compris entre 1 et 10.',
                    ])
                    ],
                    'invalid_message' => 'Le nombre de participants doit être compris entre 1 et 10.'
            ])
            ->add('average_speed', IntegerType::class, [
                'label' => 'Vitesse moyenne de la sortie',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date et heure de départ',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 d-flex'
                ]
                
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et informations complémentaires',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'rows' => '10'
                ]
            ])
            // ->add('updatedAt', DateTimeType::class, [
            //     'label' => 'Date de modification de l\'annonce',
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control m-2'
            //     ]
            // ])
            ->add('mind', EntityType::class, [
                'label' => 'Objectif de la sortie',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'class' => Mind::class,
                'data' => $user->getMind()
            ])
            ->add('practice', EntityType::class, [
                'label' => 'Pratique',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'class' => Practice::class,
                'data' => $user->getPractice()
            ])
            ->add('city', EntityType::class, [
                'label' => 'Ville de départ',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'class' => City::class
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
            'user' => UserInterface::class
        ]);
    }
}
