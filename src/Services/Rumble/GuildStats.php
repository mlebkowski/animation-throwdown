<?php

namespace Nassau\CartoonBattle\Services\Rumble;

use Doctrine\DBAL\Driver\Connection;
use Nassau\CartoonBattle\Entity\Game\UserGatherRumbleStats;
use Nassau\CartoonBattle\Entity\Rumble\Rumble;

class GuildStats
{
    /**
     * @var Connection
     */
    private $db;

    /**
     * @param Connection $db
     */
    public function __construct(Connection $db)
    {
        $this->db = $db;
    }


    public function getStats(Rumble $rumble = null, UserGatherRumbleStats $request)
    {
        $query = $this->db->prepare('
            SELECT name, user_id, match_number, points FROM rumble_result
            WHERE rumble_id = :rumble_id AND request_id = :request_id
            ORDER BY match_number ASC
        ');

        $query->execute([
            'rumble_id' => $rumble ? $rumble->getId() : null,
            'request_id' => $request->getId(),
        ]);

        $stats = $query->fetchAll(\PDO::FETCH_NUM);

        $result = [];
        foreach ($stats as $stat) {
            list ($name, $userId, $number, $points) = $stat;

            $result[$userId]['name'] = $name;
            $result[$userId]['points'][$number] = $points - array_sum($result[$userId]['points']);
        }

        usort($result, function ($alpha, $bravo) {
            return strcasecmp($alpha['name'], $bravo['name']);
        });

        return $result;
    }
}
