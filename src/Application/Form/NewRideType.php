<?php

namespace App\Application\Form;

use App\Domain\Location\City;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use App\Domain\User\User;
use DateTimeImmutable;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

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
                'attr' => ['class' => 'form-control mb-3 border border-dark border border-dark'],
                'constraints' => [
                    new Assert\Sequentially(
                        [
                            new Assert\NotBlank(
                                message: 'Titre obligatoire.'
                            ),
                            new Assert\Type(
                                type: 'string',
                                message: 'Titre invalide. Il doit être une chaine de caractères.'
                            ),
                            new Assert\Length(
                                min: 2,
                                max: 255,
                                minMessage: 'Titre invalide. Il doit être contenir au minimum {{ limit }} caractères.',
                                maxMessage: 'Titre invalide. Il doit être contenir au maximum {{ limit }} caractères.'
                            ),
                            new Assert\Regex(
                                pattern: '/^(?![×Þß÷þø])[0-9a-zA-ZÀ-ÿ\-\s\'()]{2,255}$/u',
                                message: 'Titre invalide. Il ne peut contenir que des lettres, des chiffres et des tirets.'
                            )
                        ]
                    ),
                ]
            ])
            ->add('distance', IntegerType::class, [
                'label' => 'Distance estimée (kms)',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark border border-dark'],
                'constraints' => [
                    new Assert\Sequentially(
                        [
                            new Assert\NotBlank(
                                message: 'Distance obligatoire.'
                            ),
                            new Assert\Type(
                                type: 'integer',
                                message: 'Distance invalide. Elle doit être un nombre entier.'
                            ),
                            new Assert\Positive(
                                message: 'Distance invalide. Elle doit être supérieure à 0 kms.'
                            ),
                            new Assert\Range(
                                min: 10,
                                max: 200,
                                notInRangeMessage: 'Distance invalide. Elle doit être comprise entre {{ min }} et {{ max }} kms.'
                            )
                        ]
                    ),
                ]
            ])
            ->add('ascent', IntegerType::class, [
                'label' => 'Dénivelé positif estimé (m)',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark border border-dark'],
                'constraints' => [
                    new Assert\Sequentially(
                        [
                            new Assert\NotBlank(
                                message: 'Dénivelé obligatoire.'
                            ),
                            new Assert\Type(
                                type: 'integer',
                                message: 'Dénivelé invalide. Il doit être un nombre entier.'
                            ),
                            new Assert\Positive(
                                message: 'Dénivelé invalide. Il doit être supérieur à 0 m.'
                            ),
                            new Assert\Range(
                                min: 1,
                                max: 3000,
                                notInRangeMessage: "Dénivelé invalide. Il doit être compris entre {{ min }} et {{ max }} m."
                            )
                        ]
                    ),
                ]
            ])
            ->add('maxParticipants', IntegerType::class, [
                'label' => 'Nombre maximum de participants',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark border border-dark'],
                'constraints' => [
                    new Assert\Sequentially(
                        [
                            new Assert\NotBlank(
                                message: 'Nombre de participants obligatoire.'
                            ),
                            new Assert\Type(
                                type: 'integer',
                                message: 'Nombre de participants invalide. Il doit être un nombre entier.'
                            ),
                            new Assert\Positive(
                                message: 'Nombre de participants invalide. Il doit être supérieur à 0.'
                            ),
                            new Assert\Range(
                                min: 2,
                                max: 10,
                                notInRangeMessage: 'Nombre de participants invalide. Il doit être compris entre {{ min }} et {{ max }}.'
                            )
                        ]
                    ),
                ]
            ])
            ->add('averageSpeed', IntegerType::class, [
                'label' => 'Vitesse moyenne de la sortie (km/h)',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark border border-dark'],
                'constraints' => [
                    new Assert\Sequentially(
                        [
                            new Assert\NotBlank(
                                message: 'Vitesse moyenne obligatoire.'
                            ),
                            new Assert\Type(
                                type: 'integer',
                                message: 'Vitesse moyenne invalide. Elle doit être un nombre entier.'
                            ),
                            new Assert\Positive(
                                message: 'Vitesse moyenne invalide. Elle doit être supérieure à 5 km/h.'
                            ),
                            new Assert\Range(
                                min: 5,
                                max: 50,
                                notInRangeMessage: 'Vitesse moyenne invalide. Elle doit être comprise entre {{ min }} et {{ max }} km/h.'
                            )
                        ]
                    ),
                ]
            ])
            ->add('startDate', DateTimeType::class, [
                'label' => 'Date et heure de départ',
                'required' => true,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark border border-dark',
                    'data-date-format' => 'dd-mm-yy HH:ii',
                ],
                'constraints' => [
                    new Assert\Sequentially(
                        [
                            new Assert\NotBlank(
                                message: 'Date de départ obligatoire.'
                            ),
                            new Assert\Type(
                                type: 'datetime',
                                message: 'Date de départ invalide.'
                            ),
                            new Assert\GreaterThan(
                                value: 'now',
                                message: 'La date doit être postérieure à la date du jour.'
                            ),
                        ]
                    ),
                ],
                'data' => new DateTimeImmutable('now'),
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description et informations complémentaires',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark border border-dark',
                    'rows' => '10',
                ],
                'constraints' => [
                    new Assert\Sequentially(
                        [
                            new Assert\NotBlank(
                                message: 'Description obligatoire.'
                            ),
                            new Assert\Type(
                                type: 'string',
                                message: 'Description invalide. Elle doit être une chaine de caractères.'
                            ),
                            new Assert\Length(
                                min: 2,
                                minMessage: 'Description invalide. Elle doit contenir au moins {{ limit }} caractères.'
                            ),
                            new Assert\Regex(
                                pattern: '/^[a-zA-Z0-9\s()\-\'?:.,!\/\"\p{L}]{2,255}$/u',
                                message: 'Description invalide. Les caractères spéciaux ne sont pas autorisés.'
                            )
                        ]
                    ),
                ]
            ])
            ->add('mind', EntityType::class, [
                'label' => 'Objectif',
                'required' => false,
                'placeholder' => 'Choisir un objectif',
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark border border-dark',
                ],
                'constraints' => [
                    new Assert\Sequentially(
                        [
                            new Assert\NotBlank(
                                message: 'Objectif obligatoire.'
                            ),
                            new Assert\Type(
                                type: Mind::class,
                                message: 'Objectif invalide.'
                            ),
                            new Assert\Regex(
                                pattern: '/^(?![×Þß÷þø])[a-zA-ZÀ-ÿ\-\s]{2,255}$/u',
                                message: 'Objectif invalide. Il ne peut contenir que des lettres (majuscules et minuscules) et des tirets.'
                            )
                        ]
                    ),
                ],
                'class' => Mind::class,
                'data' => $user->getMind(),
            ])
            ->add('practice', EntityType::class, [
                'label' => 'Pratique',
                'required' => false,
                'placeholder' => 'Choisir une pratique',
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
                'constraints' => [
                    new Assert\Sequentially(
                        [
                            new Assert\NotBlank(
                                message: 'Pratique obligatoire.'
                            ),
                            new Assert\Type(
                                type: Practice::class,
                                message: 'Pratique invalide.'
                            ),
                            new Assert\Regex(
                                pattern: '/^(?![×Þß÷þø])[a-zA-ZÀ-ÿ\-\s]{2,255}$/u',
                                message: 'Pratique invalide. Elle ne peut contenir que des lettres (majuscules et minuscules) et des tirets.'
                            )
                        ]
                    ),
                ],
                'class' => Practice::class,
                'data' => $user->getPractice(),
            ])
            ->add('startCity', EntityType::class, [
                'label' => 'Ville de départ',
                'required' => false,
                'placeholder' => 'Choisir une ville',
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark text-capitalize',
                ],
                'constraints' => [
                    new Assert\Sequentially(
                        [
                            new Assert\NotBlank(
                                message: 'Ville obligatoire.'
                            ),
                            new Assert\Type(
                                type: City::class,
                                message: 'Ville invalide. Elle doit être une chaine de caractères.'
                            ),
                            new Assert\Length(
                                min: 2,
                                max: 255,
                                minMessage: 'Ville invalide. Elle doit contenir au minimum {{ limit }} caractères.',
                                maxMessage: 'Ville invalide. Elle doit contenir au maximum {{ limit }} caractères.'
                            ),
                            new Assert\Regex(
                                pattern: '/^(?![×Þß÷þø])[a-zA-Z0-9À-ÿ\-\(\)\s]{2,255}$/u',
                                message: 'Ville invalide. Elle ne peut contenir de caractères spéciaux.'
                            )
                        ]
                    ),
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
            // 'data_class' => NewRideInput::class,
            'user' => User::class,
        ]);
    }
}
