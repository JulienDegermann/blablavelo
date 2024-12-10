<?php

namespace App\Application\Form;

use App\Domain\User\UseCase\CreateUser\CreateUserInput;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameNumber', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark w-100'
                ],
                'label' => 'Nom d\'utilisateur',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Nom d\'utilisateur obligatoire.',
                    ]),
                    new Assert\Type([
                        'type' => 'string',
                        'message' => 'Nom d\'utilisateur invalide. Il doit être une chaine de caractères.',
                    ]),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 255,
                        'minMessage' => 'Nom d\'utilisateur invalide. Il doit contenir au minimum {{ limit }} caractères',
                        'maxMessage' => 'Nom d\'utilisateur invalide. Il doit contenir au maximum {{ limit }} caractères',
                    ]),
                    new Assert\Regex(
                        pattern: '/^(?![×Þß÷þø])[0-9a-zA-ZÀ-ÿ\-]{2,255}$/u',
                        message: 'Nom d\'utilisateur invalide. Il peut contenir des lettres, des chiffres et des tirets.'
                    )
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark w-100',
                ],
                'constraints' => [
                    new Assert\Sequentially([

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
                    ])
                ],
            ])
            ->add('RGPDConscents', CheckboxType::class, [
                'attr' => [
                    'class' => 'w-auto mb-3 w-100'
                ],
                'required' => false,
                'label' => 'J\'accepte que ces données soient utilisées sur ce site.',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions générales d\'utilisation.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form-control mb-3 border border-dark w-100'
                ],
                'label' => 'Mot de passe *',
                'constraints' => [
                    new Assert\Sequentially([
                        new Assert\Type(
                            type: 'string',
                            message: 'Mot de passe invalide. Il doit être une chaine de caractères.'
                        ),
                        new Assert\Length(
                            min: 12,
                            max: 255,
                            minMessage: 'Mot de passe invalide. Il doit contenir au minimum {{ limit }} caractères.',
                            maxMessage: 'Mot de passe invalide. Il doit contenir au maximum {{ limit }} caractères.'
                        ),
                        new Assert\Regex(
                            pattern: '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{12,}$/',
                            message: 'Mot de passe invalide. Il n\'est pas assez sécurisé.'
                        ),
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateUserInput::class,
        ]);
    }
}
