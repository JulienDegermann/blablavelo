<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Entity\Ride;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\CityRepository;
use App\Traits\EasyAdmin\ActionsTrait;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class RideCrudController extends AbstractCrudController
{
    use ActionsTrait;

    public static function getEntityFqcn(): string
    {
        return Ride::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Sortie')
            ->setEntityLabelInPlural('Sorties')
            ->setSearchFields(['title', 'description', 'date', 'distance', 'ascent', 'max_rider', 'average_speed', 'createdAt', 'updatedAt'])
            ->setDefaultSort(['date' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = $this->configureDefaultActions($actions);

        return $actions;
    }


    private CityRepository $cityRepo;

    public function __construct(CityRepository $cityRepo)
    {
        $this->cityRepo = $cityRepo;
    }


    public function configureFields(string $pageName): iterable
    {
        yield DateTimeField::new('date', 'Date');
        yield TextField::new('title', 'Titre');
        yield AssociationField::new('city', 'Ville')
            ->setQueryBuilder(
                function (QueryBuilder $qb) {
                    return $qb
                        ->select('c')
                        ->from(City::class, 'c')
                        ->where( 'c.zip_code = :id')
                        ->setParameter('id', '56000');
                }
            );
        yield AssociationField::new('author', 'Organisateur');
        yield AssociationField::new('participants', 'Participants');
        yield AssociationField::new('practice', 'Pratique');
        yield AssociationField::new('mind', 'Objectif');
        // yield DateTimeField::new('createdAt', 'Créé le')->hideOnForm();
        // yield DateTimeField::new('updated_at', 'Modifié le')->hideOnForm();
        yield IntegerField::new('distance', 'Distance');
        yield IntegerField::new('ascent', 'D+ (m)');
        yield IntegerField::new('max_rider', 'Nombre max');
        yield IntegerField::new('average_speed', 'V.moy (km/h)');
        yield TextareaField::new('description', 'Description');
    }
}
