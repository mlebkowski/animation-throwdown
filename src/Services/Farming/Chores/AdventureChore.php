<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Game\DTO\Item;
use Nassau\CartoonBattle\Services\Game\DTO\Mission;
use Nassau\CartoonBattle\Services\Game\Game;

class AdventureChore extends AbstractBattleChore
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

        $remainingEnergy = $game->getRemainingEnergy();

        do {
            $possibleMissions = $configuration->getAdventureMissions() ?: $this->getAllPossibleMissions($game);

            $mission = Mission::fromCode($possibleMissions[array_rand($possibleMissions)]);

            $remainingEnergy -= $game->getEnergyRequired($mission);

            if ($configuration->has(UserFarming::SETTING_ADVENTURE_REFILL)) {
                $remainingEnergy = $this->refill($remainingEnergy, $game, $logWriter);
            }

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

    private function getAllPossibleMissions(Game $game)
    {
        return array_map(function ($idx) {
            return sprintf('%s-%s', ceil($idx / 3), $idx % 3 ?: 3);
        }, $game->getNextMissions() ?: range(1, 30*3));
    }

    private function refill($remainingEnergy, Game $game, \Closure $logWriter)
    {
        while ($remainingEnergy < 0) {
            foreach ([Item::ENERGY_REFILL_5 => 5, Item::ENERGY_REFILL_10 => 10] as $item => $energy) {
                if ($game->getItemCount($item)) {
                    $logWriter(sprintf('Refilling adventure energy: <comment>%d</comment>', $energy));
                    $game('useItem', ['item_id' => $item, 'number' => 1]);

                    $remainingEnergy += $energy;

                    continue 2; // while
                }
            }

            break; // no item matched!
        }

        return $remainingEnergy;
    }
}
