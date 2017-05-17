<?php

namespace Nassau\CartoonBattle\Services\Game;

use GuzzleHttp\Client;

class GameFactory
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }


    public function getGame(SynapseUserInterface $user)
    {
        return new Game($this->client, $user);
    }
}
