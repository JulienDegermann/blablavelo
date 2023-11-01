<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Mind;
use App\Entity\User;
use App\Entity\Practice;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_name', TextType::class, [
                'attr' => [
                    'class' => 'form-control my-2'
                ],
                'label' => 'Nom d\'utilisateur'
            ])
            ->add('first_name', TextType::class, [
                'attr' => [
                    'class' => 'form-control my-2'
                ],
                'label' => 'Prénom'
            ])
            ->add('last_name', TextType::class, [
                'attr' => [
                    'class' => 'form-control my-2'
                ],
                'label' => 'Nom'
            ])
            ->add('city', EntityType::class, [
                'class' => City::class,
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Ville'
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form-control my-2'
                ],
                'label' => 'E-mail'

            ])
            ->add('mind', EntityType::class, [
                'class' => Mind::class,
                'attr' => [
                    'class' => 'form-control my-2'
                ],
                'label' => 'Objectif'
            ])
            ->add('birth_date', BirthdayType::class, [
                'input' => 'datetime_immutable',
                'attr' => [
                    'class' => 'form-control my-2'
                ],
                'label' => 'Date de naissance'
            ])
            ->add('practice', EntityType::class, [
                'class' => Practice::class,
                'attr' => [
                    'class' => 'form-control my-2'
                ],
                'label' => 'Pratique'
            ])
            ->add('RGPDConscents', CheckboxType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'w-auto m-2 '
                ],
                'label' => 'J\'accepte que les données renseignées soient utilisées sur par le site',
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
                    'class' => 'form-control my-2'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci d\'entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => 'Mot de passe',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
