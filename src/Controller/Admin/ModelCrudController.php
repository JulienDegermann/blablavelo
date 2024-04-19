<?php

namespace App\Controller\Admin;

use App\Entity\Model;
use App\Traits\EasyAdmin\ActionsTrait;
use phpDocumentor\Reflection\Types\Integer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use PHPUnit\TextUI\XmlConfiguration\CodeCoverage\Report\Text;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
