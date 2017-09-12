<?php

namespace Nassau\CartoonBattle\Services\Game;

use GuzzleHttp\Client;

class GameFactory
{
    /**
     * @var Client[]
     */
    private $clients;

    /**
     * @param \ArrayAccess|Client[] $clients
     */
    public function __construct(\ArrayAccess $clients)
    {
        $this->clients = $clients;
    }


    public function getGame(SynapseUserInterface $user)
    {
        $type = HasEnvironmentType::PROD;

        if ($user instanceof HasEnvironmentType) {
            $type = $user->getEnvironmentType();
        }

        return new Game($this->clients->offsetGet($type), $user);
    }
}
