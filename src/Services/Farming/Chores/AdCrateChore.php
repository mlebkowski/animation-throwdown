<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Farming\FarmingChore;
use Nassau\CartoonBattle\Services\Game\Game;

class AdCrateChore implements FarmingChore
{

    public function make(Game $game, UserFarming $configuration, \Closure $logWriter)
    {
        foreach ([$game::ITEM_AD_CRATE, $game::ITEM_VIP_AD_CRATE] as $item) {
            $count = $game->getItemCount($item);

            for ($i = $count; $i > 0; $i--) {
                $game->useAdCrate($item);
                $logWriter("Opening an AdCrate");
                sleep(5);
            }
        }
    }
}
