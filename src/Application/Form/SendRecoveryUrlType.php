<?php

namespace App\Application\Form;

use App\Domain\User\UseCase\SendRecoveryUrl\SendRecoveryUrlInput;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SendRecoveryUrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark'
                ],
                'invalid_message' => 'L\'adresse e-mail n\'est pas valide.',
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SendRecoveryUrlInput::class,
        ]);
    }
}
