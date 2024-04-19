<?php

namespace App\Controller\Admin;

use App\Entity\Brand;
use App\Traits\EasyAdmin\ActionsTrait;

use Symfony\Component\Validator\Constraints\Date;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BrandCrudController extends AbstractCrudController
{
    use ActionsTrait;
    
    public static function getEntityFqcn(): string
    {
        return Brand::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = $this->configureDefaultActions($actions);
        
        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        return [

            TextField::new('nameNumber', 'Nom'),
            CollectionField::new('models')
                ->useEntryCrudForm()
                ->renderExpanded(true),
            DateField::new('createdAt', 'Créé le')->hideOnForm(),
            DateField::new('updatedAt', 'Modifié le')->hideOnForm()
        ];
    }
}
