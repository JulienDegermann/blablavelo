<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\Practice;
use App\Traits\EasyAdmin\ActionsTrait;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PracticeCrudController extends AbstractCrudController
{
    use ActionsTrait;

    public static function getEntityFqcn(): string
    {
        return Practice::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions =  $this->configureDefaultActions($actions);

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
            DateTimeField::new('createdAt', 'Créé le')->hideOnForm(),
            DateTimeField::new('updatedAt', 'Modifié le')->hideOnForm(),
        ];
    }
}
