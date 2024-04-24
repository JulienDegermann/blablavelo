<?php

namespace App\Form;

use App\Entity\Mind;
use App\Entity\User;
use App\Entity\Practice;
use App\Entity\Department;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\DepartmentRepository;


class ProfileType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            // ->add('first_name', TextType::class, [
            //     'label' => 'Prénom',
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control mb-3 border border-dark'                
            //     ]
            // ])
            // ->add('last_name', TextType::class, [
            //     'label' => 'Nom',
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control mb-3 border border-dark'                
            //     ]
            // ])
            // ->add('birth_date', DateType::class, [
            //     'label' => 'Date de naissance',
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control m-2',
            //     ]
            // ])
            // ->add('file', VichImageType::class, [
            //     'label' => 'Photo de profil',
            //     'required' => false,
            //     'allow_delete' => true,
            //     'image_uri' => true,
            //     'delete_label' => 'Supprimer',
            //     'download_uri' => false,
            //     'attr' => [
            //         'class' => 'form-control mb-3 border border-dark'                
            //     ]
            // ])
            ->add('email', TextType::class, [
                'label' => 'E-mail',
                'required' => true,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark'                
                ],
                'invalid_message' => 'L\'adresse e-mail n\'est pas valide.',
                'data' => $user->getEmail(),
                'mapped' => false
            ])
            ->add('department', EntityType::class, [
                'label' => 'Département',
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark text-capitalize'
                ],
                'class' => Department::class,
                'query_builder' => function (DepartmentRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.name', 'ASC');
                }
            ])
            // ->add('city', EntityType::class, [
            //     'label' => 'Ville',
            //     'class' => City::class,
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control mb-3 border border-dark'                
            //     ]
            // ])
            ->add('mind', EntityType::class, [
                'label' => 'Objectif',
                'class' => Mind::class,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark'                
                ]
            ])
            ->add('practice', EntityType::class, [
                'label' => 'Pratique',
                'class' => Practice::class,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mb-3 border border-dark'
                ]
            ])
            // ->add('bike', EntityType::class, [
            //     'label' => 'Vélo',
            //     'class' => Model::class,
            //     'required' => true,
            //     'attr' => [
            //         'class' => 'form-control mb-3 border border-dark'                
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'user' => User::class
        ]);
    }
}
