<?php

namespace App\Security\Voter;

use App\Entity\Conference;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class EditConferenceVoter implements VoterInterface
{

    public function __construct(
        private readonly AuthorizationCheckerInterface $checker,
    ) {}

    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $user = $token->getUser();
        foreach ($attributes as $attribute) {
            if (
                !$user instanceof User
                || Attributes::EDIT_CONFERENCE !== $attribute
                || !$subject instanceof Conference
            ) {
                continue;
            }

            if ($this->checker->isGranted('ROLE_WEBSITE')) {
                return self::ACCESS_GRANTED;
            }

            return $subject->getCreatedBy() === $user
                ? self::ACCESS_GRANTED
                : self::ACCESS_DENIED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
