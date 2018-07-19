<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Game\Game;

class ArenaChore extends AbstractRefillableBattleChore
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
        if (false === $configuration->has($configuration::SETTING_ARENA)) {
            return;
        }

        $stamina = $game->getRemainingStamina();

        if ($stamina <= 8) {
            return;
        }

        do {
            $stamina--;

            if ($configuration->isVIP() && $configuration->has($configuration::SETTING_ARENA_REFILL)) {
                $stamina = $this->refill($stamina, $game, $logWriter);
            }

            if ($stamina < 0) {
                return;
            }

            yield $this->getHuntingTarget($game);
        } while (true);
    }

    /**
     * @param BattleTarget $target
     * @param Game $game
     *
     * @return string
     */
    protected function startBattle(BattleTarget $target, Game $game)
    {
        return $game->startArenaBattle($target->getTarget());
    }

    private function getHuntingTarget(Game $game)
    {
        $target = $game->getRandomHuntingTarget();

        return new BattleTarget('arena', $target['name'], $target['user_id']);
    }
}
