<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Farming\DTO\Battle;
use Nassau\CartoonBattle\Services\Farming\DTO\BattleTarget;
use Nassau\CartoonBattle\Services\Game\Game;

class RumbleChore extends AbstractBattleChore
{

    /**
     * @param Game        $game
     * @param UserFarming $configuration
     * @param \Closure    $logWriter
     *
     * @return \Generator|BattleTarget[]
     */
    protected function shouldDoBattle(Game $game, UserFarming $configuration, \Closure $logWriter)
    {
        if (false === $configuration->has($configuration::SETTING_RUMBLE)) {
            return;
        }

        $rumble = $game->getRumble();

        if (false === $rumble->isActive()) {
            return;
        }

        $currentMatch = $rumble->getCurrentMatch();
        if ($currentMatch->getEndTime() > new \DateTime("90 minutes") || $rumble->getMatchStartTime() > new \DateTime) {
            return;
        }

        $energy = $rumble->getEnergy();

        while ($energy--) {
            yield new BattleTarget('rumble', $currentMatch->getEnemyName(), $currentMatch->getEnemyName());
        }

    }

    /**
     * @param BattleTarget $target
     * @param Game         $game
     *
     * @return string
     */
    protected function startBattle(BattleTarget $target, Game $game)
    {
        return new Battle($game('fightGuildWar'));
    }
}