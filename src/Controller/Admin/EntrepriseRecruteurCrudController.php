<?php

namespace App\Controller\Admin;

use App\Entity\EntrepriseRecruteur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EntrepriseRecruteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EntrepriseRecruteur::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
