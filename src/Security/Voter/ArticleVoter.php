<?php

namespace App\Security\Voter;

use App\Entity\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    public const POST_EDIT = 'EDIT';
    public const POST_VIEW = 'VIEW';
    public const POST_DELETE = 'DELETE';

    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::POST_EDIT, self::POST_VIEW, self::POST_DELETE])
            && $subject instanceof Article;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::POST_EDIT:
            case self::POST_DELETE:

                return $user->getAuthor()===$subject->getAuthor();
                // logic to determine if the user can EDIT
                // return true or false
                break;
            case self::POST_VIEW:

                return true;
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
