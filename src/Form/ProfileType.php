<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Mind;
use App\Entity\User;
use App\Entity\Model;
use App\Entity\Practice;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_name', TextType::class, [
                'label' => 'Pseudo',
                'required' => true,
                'attr' => [
                    'class' => 'form-control m-2',
                ]
            ])
            // ->add('password', PasswordType::class, [
            //     'label' => 'Mot de passe',
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control m-2',
            //     ]
            // ])
            ->add('first_name', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control m-2',
                ]
            ])
            ->add('last_name', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control m-2',
                ]
            ])
            // ->add('birth_date', DateType::class, [
            //     'label' => 'Date de naissance',
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control m-2',
            //     ]
            // ])
            ->add('file', VichImageType::class, [
                'label' => 'Photo de profil',
                'required' => false,
                'allow_delete' => true,
                'image_uri' => true,
                'delete_label' => 'Supprimer',
                'download_uri' => false,
                // 'imagine_pattern' => 'squared_thumbnail_small',
                'attr' => [
                    'class' => 'form-control m-2',
                ]
            ])
            ->add('email', TextType::class, [
                'label' => 'E-mail',
                'required' => true,
                'attr' => [
                    'class' => 'form-control m-2',
                ]
            ])
            // ->add('department', EntityType::class, [
            //     'label' => 'Département',
            //     'class' => Department::class,
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control m-2',
            //     ]
            // ])
            ->add('city', EntityType::class, [
                'label' => 'Ville',
                'class' => City::class,
                'required' => true,
                'attr' => [
                    'class' => 'form-control m-2',
                ]
            ])
            ->add('mind', EntityType::class, [
                'label' => 'Objectif',
                'class' => Mind::class,
                'required' => true,
                'attr' => [
                    'class' => 'form-control m-2',
                ]
            ])
            // ->add('rides_participated', ::class, [
            //     'label' => 'label name',
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control m-2',
            //         'value' => $user->getCity()
            //     ]
            // ])
            ->add('practice', EntityType::class, [
                'label' => 'Pratique',
                'class' => Practice::class,
                'required' => true,
                'attr' => [
                    'class' => 'form-control m-2',
                ]
            ])
            ->add('bike', EntityType::class, [
                'label' => 'Vélo',
                'class' => Model::class,
                'required' => true,
                'attr' => [
                    'class' => 'form-control m-2',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
