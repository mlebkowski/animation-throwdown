<?php

namespace Nassau\CartoonBattle\Services\Farming\Chores;

use Nassau\CartoonBattle\Entity\Game\Farming\UserFarming;
use Nassau\CartoonBattle\Services\Farming\FarmingChore;
use Nassau\CartoonBattle\Services\Game\Game;

class AdCrateChore implements FarmingChore
{

    public function make(Game $game, UserFarming $configuration, \Closure $logWriter)
    {
        $count = $game->getItemCount($game::ITEM_AD_CRATE);

        for ($i = $count; $i > 0; $i--) {
            $game->useAdCrate();
            $logWriter("Opening an AdCrate");
            sleep(5);
        }

    }
}
