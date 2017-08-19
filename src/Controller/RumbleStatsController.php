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

        foreach ($stats as $user) {
            $points = 0 === end($user['points']) ? array_slice($user['points'], 0, -1) : $user['points'];
            $response->pushRow(array_merge([$user['name']], $points));
        }

        return $response;
    }

    public function headerAction(UserGatherRumbleStats $request)
    {
        $game = $this->get('cartoon_battle.game.factory')->getGame($request->getUser());

        $rumble = $game->getRumble();

        $response = new CsvResponse();

        $matches = array_slice($rumble->getMatches(), 0, 18);

        $response->pushRow(array_map(function ($match) { return $match['them_name']; }, $matches));
        $response->pushRow(array_map(function ($match) { return $match['them_kills']; }, $matches));
        $response->pushRow(array_map(function ($match) { return $match['us_kills']; }, $matches));

        return $response;
    }
}
