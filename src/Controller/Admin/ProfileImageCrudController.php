<?php

namespace App\Controller\Admin;

use App\Entity\ProfileImage;
use App\Traits\EasyAdmin\ActionsTrait;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use Symfony\Polyfill\Intl\Icu\DateFormat\YearTransformer;

class ProfileImageCrudController extends AbstractCrudController
{
    use ActionsTrait;

    public static function getEntityFqcn(): string
    {
        return ProfileImage::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $actions = $this->configureDefaultActions($actions);

        return $actions;
    }

    public function configureFields(string $pageName): iterable
    {

        yield TextField::new('file', 'Photo de profil')
            ->setFormType(VichImageType::class)
            ->setLabel(false)
            ->setFormTypeOptions(
                [
                    'delete_label' => 'Supprimer l\'image',
                ]
            )
            ->onlyOnForms();

        yield ImageField::new('file_name', 'Photo de profile')
            ->setBasePath('/uploads/images/users')
            ->onlyOnIndex();
    }
}
