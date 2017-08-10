<?php

namespace Nassau\CartoonBattle\Services\Authentication;

use Nassau\CartoonBattle\Services\Game\GameFactory;
use Nassau\CartoonBattle\Services\Game\SynapseUserInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class ApiGameFactory
{
    /**
     * @var GameFactory
     */
    private $factory;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param GameFactory $factory
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(GameFactory $factory, TokenStorageInterface $tokenStorage)
    {
        $this->factory = $factory;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return \Nassau\CartoonBattle\Services\Game\Game|null
     */
    public function getAuthorizedGame()
    {
        $token = $this->tokenStorage->getToken();
        $user = $token ? $token->getUser() : null;

        if (false === $user instanceof SynapseUserInterface) {
            return null;
        }

        return $this->factory->getGame($user);
    }
}
