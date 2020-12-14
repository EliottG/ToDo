<?php

namespace App\Security\Voter;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class TaskOwnerVoter extends Voter
{
    const DELETE = 'delete';
    const EDIT = 'edit';
    const ADMIN = 'admin';


    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::DELETE, self::EDIT, self::ADMIN])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        $task = $subject;
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::DELETE:
                return $this->canEdit($task,$user);
                break;
            case self::EDIT:
                return $this->canDelete($task,$user);
                break;
            case self::ADMIN:
                return $this->adminPass($user);
                break;
        }

        return false;
    }
    private function canDelete(Task $task, User $user)
    {
        // if they can edit, they can view
        if ($this->canEdit($task, $user)) {
            return true;
        }

        return false;
    }

    private function adminPass(User $user)
    {
        $userRole = $user->getRoles();
        return $userRole[0] === 'ROLE_ADMIN';
    }
    private function canEdit(Task $task, User $user)
    {
        // this assumes that the Post object has a `getOwner()` method
        return $user === $task->getUser();
    }
}
