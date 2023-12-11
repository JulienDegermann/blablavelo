<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\User;
use App\Form\UserType;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', ChoiceType::class,[
                'label' => 'Objet',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3'
                ],
                'choices' => [
                    'Signaler un bug' => 'bug',
                    'Suggestion' => 'suggestion',
                ]
                ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu du message',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3',
                    'rows' => "10"
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
