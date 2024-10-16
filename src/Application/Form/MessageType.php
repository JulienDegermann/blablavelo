<?php

namespace App\Application\Form;

use App\Application\Message\Message;
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
            ->add('title', ChoiceType::class,[
                'label' => 'Objet',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark'],
                'choices' => [
                    'Signaler un bug' => 'bug',
                    'Suggestion' => 'suggestion',
                ]
                ])
            ->add('text', TextareaType::class, [
                'label' => 'Contenu du message',
                'required' => true,
                'attr' => ['class' => 'form-control mb-3 border border-dark', 'rows' => 10]
            ]);
            // ->add('author', AuthorType::class, [
            //     'label' => 'E-mail',
            //     'required' => true,
            //     'attr' => ['class' => 'form-control mb-3 border border-dark']
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
