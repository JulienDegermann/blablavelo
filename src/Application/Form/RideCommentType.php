<?php

namespace App\Application\Form;

use App\Domain\Ride\RideComment;
use App\Domain\Ride\UseCase\AddRideComment\AddRideCommentInput;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RideCommentType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('text', TextType::class, [
        'label' => false,
        'required' => true,
        'attr' => ['class' => 'form-control mb-3 border border-dark'],
      ])
      ->add('submit', SubmitType::class, [
        'label' => 'Ajouter un commentaire',
        'attr' => ['class' => 'btn btn-primary px-4 py-2 text-white mb-3'],
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => AddRideCommentInput::class,
    ]);
  }
}
