<?php

namespace Nassau\CartoonBattle\Services\Kongregate;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Nassau\CartoonBattle\Services\Game\DTO\User;
use Nassau\CartoonBattle\Services\Game\Game;

class GetGameCredentials
{
    const GAME_ID = 271381;

    /**
     * @var Client
     */
    private $client;

    /**
     * @var Game
     */
    private $game;

    /**
     * @param Client $client
     * @param Game $game
     */
    public function __construct(Client $client, Game $game)
    {
        $this->client = $client;
        $this->game = $game;
    }


    /**
     * @param string $username
     * @param string $password
     * @return User|null
     */
    public function getUser($username, $password)
    {
        $response = $this->client->post('https://www.kongregate.com/session', [
            'form_params' => [
                'game_id' => self::GAME_ID,
                'username' => $username,
                'password' => $password,
            ],
            'cookies' => new CookieJar(),
        ]);

        $data = \GuzzleHttp\json_decode($response->getBody(), true);

        if (false === isset($data['game_auth_token'])) {
            return null;
        }

        $account = $this->game->__invoke('getUserAccount', [
            'kong_token' => $data['game_auth_token'],
            'kong_id' => $data['user_id'],
            'password' => null, // erase the default user’s password
        ]);

        return new User($account['new_user'], $account['new_password'], $account['new_name']);
    }

}
