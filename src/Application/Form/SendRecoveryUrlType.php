<?php

namespace App\Application\Form;

use App\Domain\User\UseCase\SendRecoveryUrl\SendRecoveryUrlInput;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SendRecoveryUrlInput::class,
        ]);
    }
}
