<?php

namespace App\Application\Controller\Admin;

use App\Application\Traits\EasyAdmin\ActionsTrait;
use App\Domain\Bike\Model;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ModelCrudController extends AbstractCrudController
{
    use ActionsTrait;

    public static function getEntityFqcn(): string
    {
        return Model::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = $this->configureDefaultActions($actions);

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            yield TextField::new('nameNumber', 'Nom'),
            yield IntegerField::new('year', 'Année'),
        ];
    }
}
