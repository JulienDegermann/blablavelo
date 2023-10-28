<?php

namespace App\Controller\Admin;

use App\Entity\Ride;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RideCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ride::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            yield from parent::configureFields($pageName),
            yield AssociationField::new('user_creator'),
            yield AssociationField::new('user_participant'),
            yield AssociationField::new('practice'),
            yield AssociationField::new('mind'),
        ];
    }
}
