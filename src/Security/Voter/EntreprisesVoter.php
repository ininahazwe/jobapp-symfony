<?php

namespace App\Security\Voter;

use App\Entity\Entreprise;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class EntreprisesVoter extends Voter
{
    const ENTREPRISE_EDIT = 'entreprise_edit';
    const ENTREPRISE_DELETE = 'entreprise_delete';

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $entreprise): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::ENTREPRISE_EDIT, self::ENTREPRISE_DELETE])
            && $entreprise instanceof \App\Entity\Entreprise;
    }

    protected function voteOnAttribute($attribute, $entreprise, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        //Si admin
        if($this->security->isGranted('ROLE_SUPER_RECRUTEUR')) return true;

        //Vérification de l'auteur de l'entreprise

        if(null === $entreprise->getUser()) return false;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::ENTREPRISE_EDIT:
                // Peut-on éditer?
                return $this->canEdit($entreprise, $user);
                break;
            case self::ENTREPRISE_DELETE:
                // Peut-on supprimer?
                return $this->canDelete();
                break;
        }

        return false;
    }

    private function canEdit(Entreprise $entreprise, User $user): bool
    {
        return $user === $entreprise->getUser();
    }

    private function canDelete(): bool
    {
        if($this->security->isGranted('ROLE_SUPER_RECRUTEUR')) return true;
        return false;
    }
}
