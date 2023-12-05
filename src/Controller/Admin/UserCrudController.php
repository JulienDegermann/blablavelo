<?php

namespace App\Controller\Admin;

use App\Entity\User;
use DateTimeImmutable;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {

        // yield from parent::configureFields($pageName);


        yield TextField::new('user_name');
        yield TextField::new('first_name');
        yield TextField::new('last_name');

        yield TextField::new(propertyName: 'password')
            ->onlyOnForms()
            ->setFormType(PasswordType::class)
            ->setFormTypeOptions(
                [
                    'mapped' => true,
                ]
            );
        yield TextField::new('email');
        yield DateTimeField::new('birth_date')
            ->setFormTypeOption('years', range(1920, date('Y') - 18));
        yield DateTimeField::new('createdAt')
            ->setFormTypeOption('disabled', true)
            ->setFormTypeOption('years', range(2023, date('Y')));
        yield AssociationField::new('city');
        yield AssociationField::new('mind');
        yield AssociationField::new('practice');
        yield AssociationField::new('bike');
        yield ChoiceField::new('roles')
            ->setChoices([
                'user' => 'ROLE_USER',
                'moderator' => 'ROLE_MODERATOR',
                'admin' => 'ROLE_ADMIN',
            ])
            ->allowMultipleChoices();
        yield TextField::new('file', 'Photo de profil')
            ->setFormType(VichImageType::class)
            ->onlyOnForms();
        yield ImageField::new('file_name', 'Photo de profil')
            ->setBasePath('/uploads/images/users')
            ->onlyOnIndex();
    }
}
