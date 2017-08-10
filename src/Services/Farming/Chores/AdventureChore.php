<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\UserFarming;
use Nassau\CartoonBattle\Services\Game\DTO\Mission;
use Nassau\CartoonBattle\Services\Game\Game;

class AdventureChore extends AbstractBattleChore
{

    /**
     * @param Game $game
     * @param UserFarming $configuration
     *
     * @return \Generator|BattleTarget[]
     */
    protected function shouldDoBattle(Game $game, UserFarming $configuration)
    {
        if (false === $configuration->has(UserFarming::SETTING_ADVENTURE)) {
            return ;
        }

        if (false === $game->isEnergySufficient()) {
            return ;
        }

        $remainingEnergy = $game->getRemainingEnergy();

        do {
            $possibleMissions = $configuration->getAdventureMissions() ?: $this->getAllPossibleMissions();

            $mission = Mission::fromCode($possibleMissions[array_rand($possibleMissions)]);

            $remainingEnergy -= $mission->getEnergyRequired();

            if ($remainingEnergy < 0) {
                return;
            }

            yield new BattleTarget('adventure', $mission->getCode(), $mission->getMissionId());

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
        return $game->startAdventureBattle($target->getTarget());
    }

    private function getAllPossibleMissions()
    {
        return array_map(function ($idx) {
            return sprintf('%s-%s', ceil($idx / 3), $idx % 3 ?: 3);
        }, range(1, 30*3));
    }
}
