<?php

namespace App\Security\Voter;

use App\Entity\BlogPost;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class BlogPostVoter extends Voter
{
    public function __construct(
        private Security $security
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [BlogPost::EDIT,  BlogPost::VIEW])
            && $subject instanceof \App\Entity\BlogPost;
    }


    /**
     * @param BlogPost $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user  */
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        // if (!$user instanceof UserInterface) {
        //     return false;
        // }
        $isAuth = $user instanceof UserInterface;

        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case BlogPost::EDIT:
                return $isAuth
                    && (
                        ($subject->getAuthor()->getId() === $user->getId()) ||
                        $this->security->isGranted('ROLE_EDITOR')
                    );

            case BlogPost::VIEW:

                //check if the post is not subjected to extraPrivacy show the post
                if (!$subject->isExtraPrivacy()) {
                    return true;
                }

                //else you must follow the user who made the post before you can see the post
                return $isAuth &&
                    ($subject->getAuthor()->getId() === $user->getId()
                        || $subject->getAuthor()->getFollow()->contains($user)
                    );
        }

        return false;
    }
}
