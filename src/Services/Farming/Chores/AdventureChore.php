<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Farming\DTO\Battle;
use Nassau\CartoonBattle\Services\Farming\DTO\BattleTarget;
use Nassau\CartoonBattle\Services\Game\DTO\Mission;
use Nassau\CartoonBattle\Services\Game\Game;

class AdventureChore extends AbstractRefillableBattleChore
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
        if (false === $configuration->has(UserFarming::SETTING_ADVENTURE)) {
            return ;
        }

        if (false === $game->isEnergySufficient()) {
            return ;
        }

        $currentHero = $game->getHero();
        $adventureHero = $configuration->getAdventureHero();

        $changeHero = $adventureHero && $currentHero && $adventureHero->getId() != $currentHero;

        if ($changeHero) {
            $logWriter(sprintf('Adventure battles are played with <comment>%s</comment> hero', $adventureHero->getName()));
            $game->setDeckCommander($adventureHero->getId());
        }

        $remainingEnergy = $game->getRemainingEnergy();

        do {
            $possibleMissions = $configuration->getAdventureMissions() ?: $this->getAllPossibleMissions($game);

            $mission = Mission::fromCode($possibleMissions[array_rand($possibleMissions)]);

            $remainingEnergy -= $game->getEnergyRequired($mission);

            if ($configuration->isVIP() && $configuration->has(UserFarming::SETTING_ADVENTURE_REFILL)) {
                $remainingEnergy = $this->refill($remainingEnergy, $game, $logWriter);
            }

            if ($remainingEnergy < 0) {
                break;
            }

            yield new BattleTarget('adventure', $mission->getCode(), $mission->getMissionId());

        } while (true);

        if ($changeHero) {
            $game->setDeckCommander($currentHero);
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
        return new Battle($game->startAdventureBattle($target->getTarget()));
    }

    private function getAllPossibleMissions(Game $game)
    {
        return array_map(function ($idx) {
            return sprintf('%s-%s', ceil($idx / 3), $idx % 3 ?: 3);
        }, $game->getNextMissions() ?: range(1, 30*3));
    }

}
