<?php

namespace App\Application\Form;

use MindType;
use PracticeType;
use App\Domain\User\User;
use App\Domain\Location\Department;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Infrastructure\Repository\DepartmentRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use App\Domain\User\UseCase\UpdateUserSettings\UpdateUserSettingsInput;
use Symfony\Component\Validator\Constraints as Assert;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
                'data' => $user->getEmail(),
                'constraints' => [
                    new Assert\NotBlank(
                        message: 'E-mail obligatoire.'
                    ),
                    new Assert\Type(
                        type: 'string',
                        message: 'E-mail invalide.'
                    ),
                    new Assert\Email(
                        message: 'Email non valide.'
                    ),
                    new Assert\Length(
                        min: 6,
                        max: 255,
                        minMessage: 'E-mail invalide.',
                        maxMessage: 'E-mail invalide.'
                    ),
                    new Assert\Regex(
                        pattern: '/^([a-zA-Z0-9])+([a-zA-Z0-9\._-]+)*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)$/',
                        message: 'Email non valide.'
                    ),
                ],
            ])
            ->add('department', EntityType::class, [
                'label' => 'Département',
                'required' => false,
                'placeholder' => "Choisis ton départment",
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
                'data' => $user->getDepartment(),
                'class' => Department::class,
                'query_builder' => function (DepartmentRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.name', 'ASC');
                },
                'constraints' => [
                    new Assert\Type(
                        type: Department::class,
                        message: 'Département invalide.'
                    ),
                ],
            ])
            ->add('mind', EntityType::class, [
                'label' => "Objectif",
                'required' => false,
                'placeholder' => "Choisis ton objectif",
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark border border-dark',
                ],
                'constraints' => [
                    new Assert\Sequentially(
                        [
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
                'placeholder' => "Choisis ta pratique",
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
                'constraints' => [
                    new Assert\Sequentially(
                        [
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UpdateUserSettingsInput::class,
            'user' => null,
        ]);
    }
}
