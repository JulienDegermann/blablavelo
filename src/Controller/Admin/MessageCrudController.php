<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Traits\EasyAdmin\ActionsTrait;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class MessageCrudController extends AbstractCrudController
{
    use ActionsTrait;
    
    public static function getEntityFqcn(): string
    {
        return Message::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = $this->configureDefaultActions($actions);
        $actions->remove(Crud::PAGE_INDEX, 'new');

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('title', 'Titre'),
            AssociationField::new('author', 'Auteur')
                ->renderAsEmbeddedForm(),
            TextareaField::new('text', 'Message')
        ];
    }
}
