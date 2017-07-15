<?php

namespace Nassau\CartoonBattle\Controller;

use Nassau\CartoonBattle\Entity\Game\User;
use Nassau\CartoonBattle\Entity\Guild\Guild;
use Nassau\CartoonBattle\Entity\Rumble\Rumble;
use Nassau\CartoonBattle\Services\Request\CsvResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class RumbleStatsController extends Controller
{
    public function scoresAction(Guild $guild, Rumble $rumble = null)
    {
        $stats = $this->get('cartoon_battle.guild_stats')->getStats($rumble, $guild->getFactionId());

        $response = new CsvResponse();

        foreach ($stats as $user) {
            $points = 0 === end($user['points']) ? array_slice($user['points'], 0, -1) : $user['points'];
            $response->pushRow(array_merge([$user['name']], $points));
        }

        return $response;
    }

    public function headerAction(User $user)
    {
        $game = $this->get('cartoon_battle.game.factory')->getGame($user);

        $rumble = $game->getRumble();

        $response = new CsvResponse();

        $matches = $rumble->getMatches();

        $response->pushRow(array_map(function ($match) { return $match['them_name']; }, $matches));
        $response->pushRow(array_map(function ($match) { return $match['them_kills']; }, $matches));
        $response->pushRow(array_map(function ($match) { return $match['us_kills']; }, $matches));

        return $response;
    }
}
