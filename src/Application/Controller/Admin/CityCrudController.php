<?php

namespace App\Application\Controller\Admin;

use App\Application\Location\City;
use App\Application\Traits\EasyAdmin\ActionsTrait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
