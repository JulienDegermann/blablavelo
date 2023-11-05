<?php

namespace App\Controller\Admin;

use App\Entity\Ride;

use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
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

        yield from parent::configureFields($pageName);
        yield DateTimeField::new('createdAt')->setFormTypeOption('disabled', true);
        yield AssociationField::new('city');
        yield AssociationField::new('practice');
        yield AssociationField::new('mind');
        yield AssociationField::new('user_creator');
        yield AssociationField::new('user_participant');
    }
}
