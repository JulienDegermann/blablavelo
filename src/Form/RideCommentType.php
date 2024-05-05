<?php

namespace App\Form;

use App\Entity\User;
use App\Form\AuthorType;
use App\Entity\RideComment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
      'data_class' => RideComment::class,
    ]);
  }
}
