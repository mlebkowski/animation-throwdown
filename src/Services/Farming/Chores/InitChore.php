<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Farming\FarmingChore;
use Nassau\CartoonBattle\Services\Farming\LootExtractor\LootExtractor;
use Nassau\CartoonBattle\Services\Game\Game;

class InitChore implements FarmingChore
{
    /**
     * @var LootExtractor
     */
    private $lootExtractor;

    public function __construct(LootExtractor $lootExtractor)
    {
        $this->lootExtractor = $lootExtractor;
    }


    public function make(Game $game, UserFarming $configuration, \Closure $logWriter)
    {
        $result = $game->init();

        $configuration->setComment(implode("\n", [
            $game->getPlayerName(),
            $game->getPlayerGuild()->getName() ?: "<no guild>",
            $game->getArenaLevel(),
            $game->getRichness(),
        ]));

        $configuration->updateFreeTrial($game);

        $dailyRewards = [
            'monthly_daily_rewards' => 'Monthly activity rewards',
            'weekly_daily_rewards' => 'Weekly activity rewards'
        ];

        foreach ($dailyRewards as $key => $label) {
            if (isset($result[$key])) {
                $loot = $this->lootExtractor->extractLoot($result[$key]);
                if (sizeof($loot)) {
                    $logWriter(sprintf('%s: %s', $label, implode(', ', $loot)));
                }
            }
        }


    }
}