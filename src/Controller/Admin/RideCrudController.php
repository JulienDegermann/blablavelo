<?php

namespace App\Controller\Admin;

use App\Entity\Ride;
use Doctrine\DBAL\Types\IntegerType;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RideCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Ride::class;
    }

    public function configureFields(string $pageName): iterable
    {

        // yield from parent::configureFields($pageName);
        yield AssociationField::new('user_creator')->setDisabled(true);
        yield AssociationField::new('user_participant')->setDisabled(true);
        yield DateTimeField::new('createdAt')->setDisabled(true);
        yield AssociationField::new('city')->setDisabled(true)->onlyOnIndex();
        yield AssociationField::new('practice')->setDisabled(true);
        yield AssociationField::new('mind')->setDisabled(true);
        yield TextField::new('title')->setDisabled(true);
        yield DateTimeField::new('date')->setDisabled(true);
        yield DateTimeField::new('updated_at')->setDisabled(true)->hideOnForm();
        yield NumberField::new('distance')->setDisabled(true);
        yield NumberField::new('ascent')->setDisabled(true);
        yield NumberField::new('max_rider')->setDisabled(true);
        yield NumberField::new('average_speed')->setDisabled(true);
        yield TextareaField::new('description')->setDisabled(true);
    }
}
