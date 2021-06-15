<?php

namespace App\Controller\Admin;

use App\Entity\EntrepriseAdminRecruteur;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EntrepriseAdminRecruteurCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EntrepriseAdminRecruteur::class;
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
