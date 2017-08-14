<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Game\Game;

class ArenaChore extends AbstractBattleChore
{

    /**
     * @param Game $game
     * @param UserFarming $configuration
     *
     * @return \Generator|BattleTarget[]
     */
    protected function shouldDoBattle(Game $game, UserFarming $configuration)
    {
        if (false === $configuration->has($configuration::SETTING_ARENA)) {
            return;
        }

        $stamina = $game->getRemainingStamina();

        if ($stamina <= 8) {
            return;
        }

        for ($i = 0; $i < $stamina; $i++) {
            $target = $game->getRandomHuntingTarget();

            yield new BattleTarget('arena', $target['name'], $target['user_id']);
        }
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
}
