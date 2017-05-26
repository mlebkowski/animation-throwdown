<?php

namespace Nassau\CartoonBattle\Controller;

use Nassau\CartoonBattle\Entity\Rumble\Rumble;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class RumbleStatsController extends Controller
{
    public function csvAction(Rumble $rumble, $factionId)
    {
        $handle = fopen('php://memory', 'w');

        $stats = $this->get('cartoon_battle.guild_stats')->getStats($rumble, $factionId);

        foreach ($stats as $user) {
            fputcsv($handle, [$user['name']] + array_values($user['points']));
        }

        fseek($handle, 0);
        $csv = stream_get_contents($handle);

        return new Response($csv, Response::HTTP_OK, [
            'Content-type' => 'text/csv',
        ]);
    }
}
