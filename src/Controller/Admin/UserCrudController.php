<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            yield from parent::configureFields($pageName),
            yield AssociationField::new('city'),
            yield AssociationField::new('mind'),
            yield AssociationField::new('practice'),
            yield AssociationField::new('bike'),
            yield AssociationField::new('bike'),
            yield ChoiceField::new('roles')
                ->setChoices([
                    'user' => 'ROLE_USER',
                    'moderator' => 'ROLE_MODERATOR',
                    'admin' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices()
        ];
    }
}
