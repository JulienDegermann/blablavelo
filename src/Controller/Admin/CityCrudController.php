<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Entity\Department;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return City::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            yield from parent::configureFields($pageName),
            yield AssociationField::new('department'),
        ];
    }
}
