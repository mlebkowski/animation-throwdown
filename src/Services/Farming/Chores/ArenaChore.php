<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Game\Game;

class ArenaChore extends AbstractBattleChore
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

        for ($i = 0; $i < $stamina; $i++) {
            yield $this->getHuntingTarget($game, $configuration);
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

    private function getHuntingTarget(Game $game, UserFarming $configuration)
    {
        $shouldRefreshForHero = function ($target) use ($configuration) {
            $heroes = $configuration->getArenaHeroes();
            if (0 === sizeof($heroes)) {
                return false;
            }

            return false === in_array($target['hero_xp_id'], $heroes);
        };

        $refreshes = 0;
        $target = $game->getRandomHuntingTarget();
        $comment = "";

        while ($shouldRefreshForHero($target) && $refreshes++ < 10) {
            $comment = "after $refreshes refreshes";
            // refresh opponent
            $practice = $game->startPracticeBattle($target['user_id']);
            $result = $game->skipBattle($practice['battle_id']);
            if (false === isset($result['hunting_targets'])) {
                $comment = "too much refreshes, falling back";
                break; // we cant cycle tokens
            }

            $target = reset($result['hunting_targets']);

            sleep(1);
        }

        return new BattleTarget('arena', $target['name'], $target['user_id'], $comment);
    }
}
