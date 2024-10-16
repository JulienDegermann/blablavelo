<?php

namespace App\Application\Traits\EasyAdmin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;

trait ActionsTrait
{

  public function configureDefaultActions(Actions $actions): Actions
  {
    return $actions
      ->add(Crud::PAGE_INDEX, 'detail')
      ->update(Crud::PAGE_INDEX, 'edit', function (Action $action) {
        return $action->setLabel('Modifier');
      })
      ->update(Crud::PAGE_INDEX, 'new', function (Action $action) {
        return $action->setLabel('Ajouter');
      })
      ->update(Crud::PAGE_INDEX, 'delete', function (Action $action) {
        return $action->setLabel('Supprimer');
      })
      ->update(Crud::PAGE_INDEX, 'detail', function (Action $action) {
        return $action->setLabel('Détails');
      })
      ->update(Crud::PAGE_DETAIL, 'delete', function (Action $action) {
        return $action->setLabel('Supprimer');
      })
      ->update(Crud::PAGE_DETAIL, 'edit', function (Action $action) {
        return $action->setLabel('Modifier');
      })
      ->update(Crud::PAGE_DETAIL, 'index', function (Action $action) {
        return $action->setLabel('Retour à la liste');
      })
      ->update(Crud::PAGE_NEW, 'saveAndReturn', function (Action $action) {
        return $action->setLabel('Enregistrer');
      })
      ->update(Crud::PAGE_NEW, 'saveAndAddAnother', function (Action $action) {
        return $action->setLabel('Enregistrer et continuer à ajouter');
      })
      ->update(Crud::PAGE_EDIT, 'saveAndReturn', function (Action $action) {
        return $action->setLabel('Enregistrer');
      })
      ->update(Crud::PAGE_EDIT, 'saveAndContinue', function (Action $action) {
        return $action->setLabel('Enregistrer et continuer à modifier');
      });
  }
}
