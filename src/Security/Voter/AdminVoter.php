<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminVoter implements VoterInterface
{

    /**
     * @inheritDoc
     */
    public function vote(TokenInterface $token, mixed $subject, array $attributes): int
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return self::ACCESS_ABSTAIN;
        }

        if (\in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return self::ACCESS_GRANTED;
        }

        return self::ACCESS_ABSTAIN;
    }
}
