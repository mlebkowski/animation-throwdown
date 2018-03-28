<?php

namespace Nassau\CartoonBattle\Controller;

use Nassau\CartoonBattle\Entity\Game\UserGatherRumbleStats;
use Nassau\CartoonBattle\Entity\Rumble\Rumble;
use Nassau\CartoonBattle\Entity\Rumble\RumbleGuildMatch;
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

    public function headerAction(UserGatherRumbleStats $request, Rumble $rumble)
    {
        $qb = $this->get('doctrine.orm.entity_manager')->createQueryBuilder();

        /** @var RumbleGuildMatch[] $matches */
        $matches = $qb->select('match')
                ->from('CartoonBattleBundle:Rumble\RumbleGuildMatch', 'match')
                ->where('match.request = :request_id')
                ->andWhere('match.rumble = :rumble_id')
                ->setParameter('request_id', $request->getId())
                ->setParameter('rumble_id', $rumble->getId())
                ->getQuery()
            ->getResult();

        $matches = array_filter($matches, function (RumbleGuildMatch $match) {
            return $match->getUsPoints() || $match->getThemPoints() || $match->getName();
        });

        $response = new CsvResponse();

        $response->pushRow(array_map(function (RumbleGuildMatch $match) { return $match->getName(); }, $matches));
        $response->pushRow(array_map(function (RumbleGuildMatch $match) { return $match->getThemPoints(); }, $matches));
        $response->pushRow(array_map(function (RumbleGuildMatch $match) { return $match->getUsPoints(); }, $matches));

        return $response;
    }
}
