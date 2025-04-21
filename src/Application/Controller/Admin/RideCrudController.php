<?php

namespace App\Application\Controller\Admin;

use App\Application\Traits\EasyAdmin\ActionsTrait;
use App\Domain\Location\City;
use App\Domain\Ride\Ride;
use App\Infrastructure\Repository\CityRepository;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

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
            ->setDefaultSort(['startDate' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = $this->configureDefaultActions($actions);
        $actions->remove(Crud::PAGE_INDEX, 'new');

        return $actions;
    }


    private CityRepository $cityRepo;

    public function __construct(CityRepository $cityRepo)
    {
        $this->cityRepo = $cityRepo;
    }


    public function configureFields(string $pageName): iterable
    {
        yield DateTimeField::new('startDate', 'Date');
        yield TextField::new('title', 'Titre');
        yield AssociationField::new('startCity', 'Ville')
            ->setQueryBuilder(
                function (QueryBuilder $qb) {
                    return $qb
                        ->select('c')
                        ->from(City::class, 'c')
                        ->where( 'c.zip_code = :id')
                        ->setParameter('id', '56000');
                }
            );
        yield AssociationField::new('creator', 'Organisateur');
        yield AssociationField::new('participants', 'Participants');
        yield AssociationField::new('practice', 'Pratique');
        yield AssociationField::new('mind', 'Objectif');
        // yield DateTimeField::new('createdAt', 'Créé le')->hideOnForm();
        // yield DateTimeField::new('updated_at', 'Modifié le')->hideOnForm();
        yield IntegerField::new('distance', 'Distance');
        yield IntegerField::new('ascent', 'D+ (m)');
        yield IntegerField::new('maxParticipants', 'Nombre max');
        yield IntegerField::new('averageSpeed', 'V.moy (km/h)');
        yield TextareaField::new('description', 'Description');
        yield CollectionField::new('rideComments', 'Commentaires')->renderExpanded()->setEntryIsComplex()->useEntryCrudForm();
    }
}
