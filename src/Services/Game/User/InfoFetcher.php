<?php


namespace Nassau\CartoonBattle\Services\Game\User;


use Doctrine\Common\Cache\Cache;
use Nassau\CartoonBattle\Services\Game\Game;

class InfoFetcher
{
    /**
     * @var Cache
     */
    private $cache;

    /**
     * @var Game
     */
    private $game;

    public function __construct(Cache $cache, Game $game)
    {
        $this->cache = $cache;
        $this->game = $game;
    }

    public function getUserInfo($id)
    {
        if ($this->cache->contains($id)) {
            return $this->cache->fetch($id);
        }

        $data = $this->game->getUserProfile($id);

        $this->cache->save($id, $data, 60*60*8);

        return $data;
    }


}
