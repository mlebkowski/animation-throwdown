<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\UserFarming;
use Nassau\CartoonBattle\Services\Farming\FarmingChore;
use Nassau\CartoonBattle\Services\Game\Game;

class QuestsChore implements FarmingChore
{

    public function make(Game $game, UserFarming $configuration, \Closure $logWriter)
    {
        if (false === $configuration->has($configuration::SETTING_CARDS)) {
            return ;
        }

        do {
            $quests = [$game->getQuest(5010), $game->getQuest(5009)];

            $pendingQuests = array_reduce($quests, function ($result, $quest) {
                return $result || ($quest && false === (bool)$quest['status']);
            }, false);

            if (false === $pendingQuests) {
                break;
            }

            $unit = $game->buySinglePackItem();

            if (!$unit) {
                break;
            }

            $unit = reset($unit);

            sleep(1);

            $logWriter('Bought one single pack, upgrading the cards now');

            $game->upgradeUnit($unit['unit_index']);

            sleep(1);

        } while (false);

        foreach ($game->completeAllAchievements() as $achievement) {
            $logWriter(sprintf('Quest complete: <comment>%s</comment>', $achievement));
        }
    }
}
