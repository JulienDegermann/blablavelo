<?php

namespace App\Application\Form;

use App\Domain\Location\Department;
use App\Domain\PracticeDetail\Mind;
use App\Domain\PracticeDetail\Practice;
use App\Domain\User\UseCase\UpdateUserSettings\UpdateUserSettingsInput;
use App\Domain\User\User;
use App\Infrastructure\Repository\DepartmentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('email', TextType::class, [
                'label' => 'E-mail',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
                'invalid_message' => 'L\'adresse e-mail n\'est pas valide.',
                'data' => $user->getEmail(),
            ])
            ->add('department', EntityType::class, [
                'label' => 'Département',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark text-capitalize',
                ],
                'class' => Department::class,
                'query_builder' => function (DepartmentRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.name', 'ASC');
                },
            ])
            ->add('mind', EntityType::class, [
                'label' => 'Objectif',
                'class' => Mind::class,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
            ])
            ->add('practice', EntityType::class, [
                'label' => 'Pratique',
                'class' => Practice::class,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark',
                ],
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
