<?php

namespace App\Application\Form;

use App\Domain\User\UseCase\ResetPassword\ResetPasswordInput;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', RepeatedType::class, [
                'label' => false,
                'type' => PasswordType::class,
                'required' => true,
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
                ],
                'options' => [
                    'attr' => [
                        'class' => 'form-control mb-3 border border-dark'
                    ],
                ],
                'first_options' => [
                    'label' => 'Mot de passe',
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe',
                    'invalid_message' => 'Les mots de passe ne correspondent pas.',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ResetPasswordInput::class,
        ]);
    }
}
