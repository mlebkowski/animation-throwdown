<?php

namespace Nassau\CartoonBattle\Controller;

use Nassau\CartoonBattle\Entity\Game\UserGatherRumbleStats;
use Nassau\CartoonBattle\Entity\Rumble\Rumble;
use Nassau\CartoonBattle\Services\Request\CsvResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RumbleStatsController extends Controller
{
    public function scoresAction(UserGatherRumbleStats $request, Rumble $rumble = null)
    {
        $stats = $this->get('cartoon_battle.guild_stats')->getStats($rumble, $request);

        $response = new CsvResponse();

        foreach ($stats as $rumbleId => $users) {
            foreach ($users as $user) {
                $points = 0 === end($user['points']) ? array_slice($user['points'], 0, -1) : $user['points'];
                $response->pushRow(array_merge(
                    (null === $rumble) ? [$rumbleId] : [],
                    [$user['name']],
                    $points
                ));
            }
        }

        return $response;
    }

    public function headerAction(UserGatherRumbleStats $request, Rumble $rumble = null)
    {
        $stats = $this->get('cartoon_battle.guild_stats')->getMatches($rumble, $request);

        $response = new CsvResponse();


        $getRow = function ($matches, $rumbleId, $key) use ($rumble) {
            return array_merge(
                null === $rumble ? [$rumbleId] : [],
                array_map(function ($match) use ($key) {
                    return $match[$key];
                }, $matches)
            );
        };

        foreach ($stats as $rumbleId => $matches) {
            $response->pushRow($getRow($matches, $rumbleId, 'name'));
            $response->pushRow($getRow($matches, $rumbleId, 'them_points'));
            $response->pushRow($getRow($matches, $rumbleId, 'us_points'));
        }

        return $response;
    }
}
