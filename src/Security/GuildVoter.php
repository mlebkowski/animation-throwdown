<?php

namespace Nassau\CartoonBattle\Security;

use Nassau\CartoonBattle\Entity\Guild\Guild;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class GuildVoter implements VoterInterface
{
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if ($object instanceof Guild) {
            if ($this->decisionManager->decide($token, ['ROLE_SUPER_ADMIN', 'ROLE_GUILDS'])) {
                return self::ACCESS_GRANTED;
            }
        }

        return self::ACCESS_ABSTAIN;
    }


    /**
     * {@inheritdoc}
     */
    public function supportsAttribute($attribute)
    {
        throw new \BadMethodCallException('supportsAttribute method is deprecated since version 2.8, to be removed in 3.0');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsClass($class)
    {
        throw new \BadMethodCallException('supportsClass method is deprecated since version 2.8, to be removed in 3.0');
    }
}
