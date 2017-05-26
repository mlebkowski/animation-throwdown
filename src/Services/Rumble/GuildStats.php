<?php

namespace Nassau\CartoonBattle\Services\Rumble;

use Doctrine\DBAL\Driver\Connection;
use Nassau\CartoonBattle\Entity\Rumble\Rumble;
use Nassau\CartoonBattle\Services\Game\Game;

class GuildStats
{
    /**
     * @var Game
     */
    private $game;

    /**
     * @var Connection
     */
    private $db;

    /**
     * @param Game $game
     * @param Connection $db
     */
    public function __construct(Game $game, Connection $db)
    {
        $this->game = $game;
        $this->db = $db;
    }


    public function getStats(Rumble $rumble, $factionId)
    {
        $guild = $this->game->getGuildInfo($factionId);

        $result = array_combine(array_column($guild['members'], 'user_id'), array_map(function ($user) {
            return [
                'name' => $user['name'],
                'points' => [],
            ];
        }, $guild['members']));

        /** @noinspection PhpMethodParametersCountMismatchInspection */
        $stats = $this->db->query(sprintf(
            'SELECT user_id, match_number, points FROM rumble_result 
            WHERE rumble_id = %d AND user_id in (%s) ORDER BY match_number ASC',
            $rumble->getId(),
            implode(',', array_map('intval', array_keys($result)))
        ))->fetchAll(\PDO::FETCH_NUM);

        foreach ($stats as $stat) {
            list ($userId, $number, $points) = $stat;

            $result[$userId]['points'][$number] = $points - array_sum($result[$userId]['points']);
        }

        usort($result, function ($alpha, $bravo) {
            return strcasecmp($alpha['name'], $bravo['name']);
        });

        return $result;
    }
}
