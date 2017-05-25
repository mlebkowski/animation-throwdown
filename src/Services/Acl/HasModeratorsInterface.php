<?php

namespace Nassau\CartoonBattle\Services\Acl;

use Symfony\Component\Security\Acl\Model\ObjectIdentityInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface HasModeratorsInterface extends ObjectIdentityInterface
{
    /**
     * @return UserInterface[]
     */
    public function getModerators();
}
