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
        $query = $this->db->prepare($this->ifRumble($rumble, '
            SELECT rumble_id, name, user_id, match_number, points FROM rumble_result
            WHERE request_id = :request_id
            ORDER BY rumble_id DESC, match_number ASC, name ASC
        '));

        $query->execute(array_filter([
            'rumble_id' => $rumble ? $rumble->getId() : null,
            'request_id' => $request->getId(),
        ]));

        $stats = $query->fetchAll(\PDO::FETCH_NUM);

        $result = [];
        foreach ($stats as $stat) {
            list ($rumbleId, $name, $userId, $number, $points) = $stat;

            $currentValue = isset($result[$rumbleId][$userId]['points']) ? array_sum($result[$rumbleId][$userId]['points']?:[]) : 0;
            $result[$rumbleId][$userId]['name'] = $name;
            $result[$rumbleId][$userId]['rumble'] = $rumbleId;
            $result[$rumbleId][$userId]['points'][$number] = $points - $currentValue;
        }

        return $result;
    }

    public function getMatches(Rumble $rumble = null, UserGatherRumbleStats $request)
    {
        $query = $this->db->prepare($this->ifRumble($rumble, '
            SELECT
                us_points,
                them_points,
                match_number,
                name,
                rumble_id
            FROM rumble_guild_match
            WHERE request_id = :request_id
              AND us_points > 0 
              AND them_points > 0
              AND name != ""
            ORDER BY rumble_id DESC, match_number ASC
        '));

        $query->execute(array_filter([
            'rumble_id' => $rumble ? $rumble->getId() : null,
            'request_id' => $request->getId(),
        ]));

        $rows = $query->fetchAll(\PDO::FETCH_ASSOC);

        $matches = [];
        foreach ($rows as $row) {
            $matches[$row['rumble_id']][] = $row;
        }

        return $matches;
    }

    private function ifRumble(Rumble $rumble = null, $query)
    {
        if ($rumble) {
            return str_replace('WHERE', 'WHERE rumble_id = :rumble_id AND', $query);
        }

        return $query;
    }
}
