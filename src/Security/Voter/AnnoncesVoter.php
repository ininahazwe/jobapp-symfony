<?php

namespace App\Security\Voter;

use App\Entity\Annonce;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class AnnoncesVoter extends Voter
{
    const ANNONCE_EDIT = 'annonce_edit';
    const ANNONCE_DELETE = 'annonce_delete';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $annonce): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::ANNONCE_EDIT, self::ANNONCE_DELETE])
            && $annonce instanceof \App\Entity\Annonce;
    }

    protected function voteOnAttribute($attribute, $annonce, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //Si admin
        if($this->security->isGranted('ROLE_COMMUNICANT')) return true;

        //Vérification de l'auteur de l'annonce

        if(null === $annonce->getAuteur()) return false;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ANNONCE_EDIT:
                // Peut-on éditer?
                return $this->canEdit($annonce, $user);
                break;
            case self::ANNONCE_DELETE:
                // Peut-on supprimer?
                return $this->canDelete();
                break;
        }

        return false;
    }

    private function canEdit(Annonce $annonce, User $user): bool
    {
        return $user === $annonce->getAuteur();
    }

    private function canDelete(): bool
    {
        if($this->security->isGranted('ROLE_RECRUTEUR')) return true;
        return false;
    }
}
