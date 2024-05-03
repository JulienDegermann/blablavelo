<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Mind;
use App\Entity\Ride;
use App\Entity\User;
use App\Entity\Model;
use App\Entity\Practice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class PasswordForgotType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark'
                ],
                'invalid_message' => 'L\'adresse e-mail n\'est pas valide.',

            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
