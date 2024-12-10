<?php

namespace App\Application\Form;

use App\Domain\Message\Message;
use App\Domain\Message\UseCase\SendMessage\SendMessageInput;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', ChoiceType::class, [
                'label' => 'Objet',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark'],
                'choices' => [
                    'Signaler un bug' => 'bug',
                    'Suggestion' => 'suggestion',
                ],
            ])
            ->add('text', TextareaType::class, [
                'label' => 'Contenu du message',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark', 'rows' => 10],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SendMessageInput::class,
        ]);
    }
}
