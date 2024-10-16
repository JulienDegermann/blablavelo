<?php

namespace App\Application\Form;

use App\Domain\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark'
                ],
                'label' => 'Nom d\'utilisateur'
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark'
                ],
                'label' => 'E-mail'

            ])
            ->add('RGPDConscents', CheckboxType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'w-auto mb-3 '
                ],
                'label' => 'J\'accepte que ces données soient utilisées sur ce site',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepterles conditions',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control mb-3 border border-dark'
                ],
                'label' => 'Mot de passe'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
