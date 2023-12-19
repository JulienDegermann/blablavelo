<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\City;
use App\Entity\Mind;
use App\Entity\Ride;
use App\Entity\Department;
use DateTimeImmutable;
use App\Entity\Practice;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class NewRideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        /** @var User $user */
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
                'label' => 'Dénivelé positif estimé (m)',
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
                        'notInRangeMessage' => 'Le nombre doit être compris entre 1 et 10.'
                    ])
                    ]
            ])
            ->add('average_speed', IntegerType::class, [
                'label' => 'Vitesse moyenne de la sortie (km/h)',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date et heure de départ',
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control mb-3 d-flex',
                    'data-date-format' => 'dd-mm-yy HH:ii'
                ],
                'data' => new \DateTime('now')      
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et informations complémentaires',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'rows' => '10'
                ]
            ])
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
                    'class' => 'form-control mb-3 text-capitalize'
                ],
                'class' => City::class,
                'query_builder' => function (EntityRepository $er) use ($user): QueryBuilder {
                            return $er->createQueryBuilder('c')
                                ->leftJoin('c.department', 'd')
                                ->andWhere('c.department = :d')
                                ->setParameter(':d', $user->getDepartment())
                                ->orderBy('c.name', 'ASC');
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ride::class,
            'user' => User::class
        ]);
    }
}
