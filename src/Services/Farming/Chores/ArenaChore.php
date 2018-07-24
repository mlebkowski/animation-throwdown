<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Farming\DTO\Battle;
use Nassau\CartoonBattle\Services\Farming\DTO\BattleTarget;
use Nassau\CartoonBattle\Services\Farming\DTO\FailedToStartBattle;
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

        $failedBattles = 0;
        $battleLimit = 35;

        do {
            $stamina--;

            if ($configuration->isVIP() && $configuration->has($configuration::SETTING_ARENA_REFILL)) {
                $stamina = $this->refill($stamina, $game, $logWriter);
            }

            if ($stamina < 0) {
                return;
            }

            $success = (yield $this->getHuntingTarget($game));

            if ($success instanceof FailedToStartBattle) {
                $stamina++;
                $failedBattles++;

                if ($failedBattles > 15) {
                    $logWriter('<error>Failed over 15 battles, something’s wrong, aborting!</error>');

                    return;
                }
            }

            if (--$battleLimit <= 0) {
                $logWriter('<error>Hit a limit of 35 battles, aborting!</error>');

                return;
            }

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
        return new Battle($game->startArenaBattle($target->getTarget()));
    }

    private function getHuntingTarget(Game $game)
    {
        sleep(2);

        $target = $game->getRandomHuntingTarget();

        $message = $target['player_dialog'] ?: $target['opponent_dialog'];

        return new BattleTarget('arena', sprintf('%s (%s)', $target['name'], $message), 0);
    }
}
