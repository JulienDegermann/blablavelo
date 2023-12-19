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


        yield TextField::new('user_name')
            ->setDisabled(true);
        yield TextField::new('first_name')
            ->setDisabled(true);
        yield TextField::new('last_name')
            ->setDisabled(true);
        yield TextField::new(propertyName: 'password')
            ->onlyOnForms()
            ->setFormType(PasswordType::class)
            ->onlyWhenCreating();
        yield TextField::new('email')
            ->setDisabled(true);
        yield DateTimeField::new('birth_date')
            ->setFormTypeOption('years', range(1920, date('Y') - 18))
            ->setDisabled(true);
        yield DateTimeField::new('createdAt')
            ->setFormTypeOption('disabled', true)
            ->setFormTypeOption('years', range(2023, date('Y')));
        yield AssociationField::new('city')
            ->onlyWhenCreating()
            ->setDisabled(true);
        yield AssociationField::new('mind')
            ->onlyWhenCreating()
            ->setDisabled(true);
        yield AssociationField::new('practice')
            ->onlyWhenCreating()
            ->setDisabled(true);
        yield AssociationField::new('bike')
            ->setDisabled(true);
        yield ChoiceField::new('roles')
            ->setChoices([
                'user' => 'ROLE_USER',
                'moderator' => 'ROLE_MODERATOR',
                'admin' => 'ROLE_ADMIN',
            ])
            ->onlyWhenCreating()
            ->allowMultipleChoices();
        yield TextField::new('file', 'Photo de profil')
            ->setFormType(VichImageType::class)
            ->onlyOnForms()
            ->onlyWhenCreating();
        yield ImageField::new('file_name', 'Photo de profil')
            ->setBasePath('/uploads/images/users')
            ->onlyOnIndex();
    }
}
