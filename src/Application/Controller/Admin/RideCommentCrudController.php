<?php

namespace App\Application\Controller\Admin;

use App\Domain\Ride\RideComment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class RideCommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RideComment::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextareaField::new('text', 'Commentaire'),
            AssociationField::new('author', 'Auteur'),
            DateTimeField::new('createdAt', 'Date de création'),
            DateTimeField::new('updatedAt', 'Date de modification'),
        ];
    }

}
