<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Traits\EasyAdmin\ActionsTrait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CityCrudController extends AbstractCrudController
{
    use ActionsTrait;
    
    public static function getEntityFqcn(): string
    {
        return City::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = $this->configureDefaultActions($actions);
        
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('name', 'Nom'),
            TextField::new('zip_code', 'Code postal'),
            AssociationField::new('department', 'Département'),
        ];
    }
}
