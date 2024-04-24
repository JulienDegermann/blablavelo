<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

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
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control mb-3 border border-dark'
                ],
                'label' => 'Mot de passe',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        'maxMessage' => 'Votre mot de passe doit contenir au maximum {{ limit }} caractères',
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
