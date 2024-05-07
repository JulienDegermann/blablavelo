<?php

namespace App\Controller\Admin;

use DateTime;
use App\Entity\RideComment;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

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
