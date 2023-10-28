<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
            // yield AssociationField::new('rides_created'),
            // yield AssociationField::new('rides_participated'),
            yield AssociationField::new('mind'),
            yield AssociationField::new('practice'),
            yield AssociationField::new('bike'),
        ];
    }
}
