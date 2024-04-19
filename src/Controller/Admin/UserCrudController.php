<?php

namespace App\Controller\Admin;


use App\Entity\User;
use App\Form\ProfileImageType;
use App\Traits\EasyAdmin\ActionsTrait;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;

class UserCrudController extends AbstractCrudController
{
    use ActionsTrait;

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = $this->configureDefaultActions($actions);

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('nameNumber', 'Pseudo')
            ->setDisabled(false);
        yield TextField::new('email', 'Email')
            ->hideOnIndex()
            ->setDisabled(false);
        yield TextField::new('firstName', 'Prénom')
            ->setDisabled(false);
        yield TextField::new('lastName', 'Nom')
            ->setDisabled(false);
        yield TextField::new('password', 'Mot de passe')
            ->onlyOnForms()
            ->setFormType(PasswordType::class)
            ->onlyWhenCreating();
        yield DateTimeField::new('birth_date', 'Date de naissance')
            ->setFormTypeOption('years', range(1920, date('Y') - 18))
            ->hideOnIndex()
            ->setDisabled(false);
        yield DateTimeField::new('createdAt', "Créé le")
            ->hideOnForm();
        yield AssociationField::new('mind', 'Objectif')
            ->onlyWhenCreating()
            ->setDisabled(false);
        yield AssociationField::new('practice', 'Pratique')
            ->onlyWhenCreating()
            ->setDisabled(false);
        yield BooleanField::new('isVerified', 'Vérifié')
            ->renderAsSwitch(false);
        yield ChoiceField::new('roles')
            ->setChoices([
                'user' => 'ROLE_USER',
                'moderator' => 'ROLE_MODERATOR',
                'admin' => 'ROLE_ADMIN',
            ])
            ->onlyWhenCreating()
            ->allowMultipleChoices();
        yield AssociationField::new('bike', 'Vélo');
        // yield AssociationField::new('profileImage', 'Photo de profil')
        yield ImageField::new('profileImage', 'Photo de profil')
            ->setBasePath('/uploads/images/users')
            ->onlyOnIndex();        
        // yield AssociationField::new('profileImage', 'Photo de profil')
        //     ->renderAsEmbeddedForm()
        //     ->setCrudController(ProfileImageCrudController::class)
        //     ->onlyOnForms();
        yield AssociationField::new('profileImage', 'Photo de profil')
            ->renderAsEmbeddedForm()
            ->onlyOnForms();
    }
}
