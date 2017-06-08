<?php

namespace Nassau\CartoonBattle\Controller;

use Nassau\CartoonBattle\Entity\Guild\Guild;
use Nassau\CartoonBattle\Entity\Rumble\Rumble;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RumbleStatsController extends Controller
{
    public function csvAction(Guild $guild, Rumble $rumble = null)
    {
        $handle = fopen('php://memory', 'w');

        $stats = $this->get('cartoon_battle.guild_stats')->getStats($rumble, $guild->getFactionId());

        foreach ($stats as $user) {
            $points = 0 === end($user['points']) ? array_slice($user['points'], 0, -1) : $user['points'];
            fputcsv($handle, array_merge([$user['name']], $points));
        }

        fseek($handle, 0);
        $csv = stream_get_contents($handle);

        return new Response($csv, Response::HTTP_OK, [
            'Content-type' => 'text/csv',
        ]);
    }
}
